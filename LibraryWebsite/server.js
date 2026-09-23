
const Database = require('./database');
const express = require('express');
const path = require('path');
const app = express();

const db = new Database('database.db');

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

//Use an API endpoint to handle login requests


app.use(express.static(path.join(__dirname, 'public')));

app.listen(3000, () => {
    console.log('Server is running on http://localhost:3000');
});

//http://localhost:3000/login.html                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              