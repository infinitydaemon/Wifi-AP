const https = require('https');
const owner = 'infinitydaemon';
const repo = 'Wifi-AP';
const folder = 'config';

const accessToken = ''; // Replace with your access token if needed

const options = {
  hostname: 'api.github.com',
  port: 443,
  path: `/repos/${owner}/${repo}/commits?path=${folder}`,
  method: 'GET',
  headers: {
    'User-Agent': 'node.js',
    'Accept': 'application/vnd.github+json'
  }
};
if (accessToken) {
  options.headers['Authorization'] = `Bearer ${accessToken}`;
}

const req = https.request(options, res => {
  let data = '';
  res.on('data', chunk => {
    data += chunk;
  });
  res.on('end', () => {
    const commits = JSON.parse(data);
    if (commits.length > 0) {
      console.log(`Changes detected in ${folder}:`);
      for (const commit of commits) {
        console.log(`- ${commit.commit.message} (${commit.author.login} at ${commit.commit.author.date})`);
      }
    } else {
      console.log(`No changes detected in ${folder}.`);
    }
  });
});
req.on('error', error => {
  console.error(error);
});
req.end();
