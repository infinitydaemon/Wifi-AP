
# Copyright (C) 2023, CWD Systems <cwdsystems-at-protonmail.ch>
# Monitor , block and re-monitor intrusion attemps in NodeJS

import requests
import smtplib
import os

owner = 'username'
repo = 'repository-name'

local_path = '/path/to/local/repo'

smtp_server = 'smtp.gmail.com'
smtp_port = 587
sender_email = 'sender@gmail.com'
sender_password = 'sender_password'
receiver_email = 'receiver@gmail.com'

response = requests.get(f'https://api.github.com/repos/{owner}/{repo}/commits/master')
github_sha = response.json()['sha']

os.chdir(local_path)
os.system('git fetch origin master')
local_sha = os.popen('git rev-parse origin/master').read().strip()

if local_sha != github_sha:
    message = f'Subject: {owner}/{repo} has been updated\n\nThe repository {owner}/{repo} has been updated on GitHub.'
    with smtplib.SMTP(smtp_server, smtp_port) as server:
        server.starttls()
        server.login(sender_email, sender_password)
        server.sendmail(sender_email, receiver_email, message)

        # Add 0 * * * * /usr/bin/python3 /path/to/gicheck.py after entering creds.
        
