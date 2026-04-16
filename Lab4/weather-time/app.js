const express = require("express");
const hbs = require("hbs");
const https = require("https");
const mysql = require("mysql2");

const app = express();
const PORT = 3000;

const API_KEY = "c58dda19ec8272d20370a7857eda9af8";

// підключення до Бази даних
const db = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "",
  database: "weather_app"
});

app.set("view engine", "hbs");
hbs.registerPartials(__dirname + "/views/partials");

// Список міст для вибору
const cities = [
    { name: "Київ", query: "Kyiv" },
    { name: "Львів", query: "Lviv" },
    { name: "Одеса", query: "Odesa" },
    { name: "Харків", query: "Kharkiv" },
    { name: "Дніпро", query: "Dnipro" },
    { name: "Вінниця", query: "Vinnytsia" }
];

// Головна сторінка з вибором міст
app.get("/", (req, res) => {
  res.render("index", { cities });
});

// Погода для вибраного з міст
app.get("/weather/:city", (req, res) => {
  const city = req.params.city;

  const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${API_KEY}&units=metric`;

  https.get(url, (response) => {
    let data = "";

    response.on("data", chunk => data += chunk);

    response.on("end", () => {
      const json = JSON.parse(data);

      if (json.cod !== 200) {
        return res.render("weather", { error: "Місто не знайдено" });
      }

        const utcTime = new Date(json.dt * 1000);
        const localTime = new Date(utcTime.getTime() + json.timezone * 1000);

        const formattedDate = localTime.toLocaleDateString("uk-UA");
        const formattedTime = localTime.toLocaleTimeString("uk-UA", {
        hour: '2-digit',
        minute: '2-digit'
        });

        const weatherData = {
            city: json.name,
            temp: json.main.temp,
            description: json.weather[0].description,
            humidity: json.main.humidity,
            pressure: json.main.pressure,
            date: formattedDate,
            time: formattedTime
        };

      // запис у Базу даних
      db.query(
        "INSERT INTO weather_logs (city, temperature, description) VALUES (?, ?, ?)",
        [weatherData.city, weatherData.temp, weatherData.description]
      );

        res.render("weather", {
            cityName: weatherData.city,
            temp: weatherData.temp,
            description: weatherData.description,
            humidity: weatherData.humidity,
            pressure: weatherData.pressure,
            date: weatherData.date,
            time: weatherData.time
        });
    });
  });
});

// сторінка "моє місто"
app.get("/my-city", (req, res) => {
  res.render("mycity");
});

// обробка введеного міста
app.get("/my-city-weather", (req, res) => {
  const city = req.query.city;

  const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${API_KEY}&units=metric`;

  https.get(url, (response) => {
    let data = "";

    response.on("data", chunk => data += chunk);

    response.on("end", () => {
      const json = JSON.parse(data);

      if (json.cod !== 200) {
        return res.render("weather", { error: "Місто не знайдено" });
      }

        const utcTime = new Date(json.dt * 1000);
        const localTime = new Date(utcTime.getTime() + json.timezone * 1000);

        const formattedDate = localTime.toLocaleDateString("uk-UA");
        const formattedTime = localTime.toLocaleTimeString("uk-UA", {
        hour: '2-digit',
        minute: '2-digit'
        });

        const weatherData = {
            city: json.name,
            temp: json.main.temp,
            description: json.weather[0].description,
            humidity: json.main.humidity,
            pressure: json.main.pressure,
            date: formattedDate,
            time: formattedTime
        };

      db.query(
        "INSERT INTO weather_logs (city, temperature, description) VALUES (?, ?, ?)",
        [weatherData.city, weatherData.temp, weatherData.description]
      );

        res.render("weather", {
            cityName: weatherData.city,
            temp: weatherData.temp,
            description: weatherData.description,
            humidity: weatherData.humidity,
            pressure: weatherData.pressure,
            date: weatherData.date,
            time: weatherData.time
        });
    });
  });
});

// видалення запису (міста) по id
app.get("/delete/:id", (req, res) => {
  const id = req.params.id;

  db.query("DELETE FROM weather_logs WHERE id = ?", [id], () => {
    res.redirect("/history");
  });
});

// сторінка історії
app.get("/history", (req, res) => {
  db.query("SELECT * FROM weather_logs ORDER BY id DESC", (err, results) => {
    if (err) {
      console.log(err);
      return res.send("Помилка БД");
    }

    res.render("history", { logs: results });
  });
});

// сторінка вибору (погода на 5 днів вперед)
app.get("/future", (req, res) => {
  res.render("future");
});

app.get("/future-weather", (req, res) => {
  const city = req.query.city;
  const selectedDate = req.query.date;

  const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${API_KEY}&units=metric`;

  https.get(url, (response) => {
    let data = "";

    response.on("data", chunk => data += chunk);

    response.on("end", () => {
      const json = JSON.parse(data);

      if (json.cod !== "200") {
        return res.render("weather", { error: "Місто не знайдено" });
      }

        // шукаємо прогноз по даті
        const forecasts = json.list.filter(item =>
          item.dt_txt.startsWith(selectedDate)
        );

        const forecast = forecasts.find(item =>
          item.dt_txt.includes("12:00:00")
        ) || forecasts[0];

      if (!forecast) {
        return res.render("weather", { error: "Немає прогнозу на цю дату" });
      }

      db.query(
        "INSERT INTO weather_logs (city, temperature, description) VALUES (?, ?, ?)",
        [
            json.city.name + " (прогноз " + selectedDate + ")",
            forecast.main.temp,
            forecast.weather[0].description
        ]);

      res.render("weather", {
        cityName: json.city.name,
        temp: forecast.main.temp,
        description: forecast.weather[0].description,
        humidity: forecast.main.humidity,
        pressure: forecast.main.pressure,
        date: selectedDate
      });
    });
  });
});

app.listen(PORT, () => {
  console.log("http://localhost:3000");
});
