// Copyright (C) 2023, CWD Systems <cwdsystems-at-protonmail.ch>
// Monitor , block and re-monitor intrusion attemps in NodeJS

const http = require('http');
const cron = require('node-cron');
const exec = require('child_process').exec;

const urlToCheck = 'http://www.google.com';

function checkConnectivity() {
  http.get(urlToCheck, (res) => {
    if (res.statusCode !== 200) {
      console.log(`Error: ${res.statusCode}`);
      exec('sudo ufw deny from any to any port 80');
    } else {
      console.log('Internet connectivity OK');
      exec('sudo ufw delete deny from any to any port 80');
    }
  }).on('error', (err) => {
    console.log(`Error: ${err.message}`);
    exec('sudo ufw deny from any to any port 80');
  });
}

cron.schedule('* * * * *', () => {
  checkConnectivity();
});
