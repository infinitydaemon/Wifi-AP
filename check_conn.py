# Check if internet connection is still active. Script should be run via a secondary screen session in an critical environment.
import requests
import smtplib
import time

def check_internet_connection():
    url = "https://www.google.com"
    timeout = 5
    try:
        response = requests.get(url, timeout=timeout)
        response.raise_for_status()
        return True
    except:
        return False

def send_email(subject, message, sender_email, sender_password, receiver_email):
    try:
        server = smtplib.SMTP('smtp.gmail.com', 587)
        server.starttls()
        server.login(sender_email, sender_password)
        body = '\r\n'.join(['To: %s' % receiver_email,
                            'From: %s' % sender_email,
                            'Subject: %s' % subject,
                            '', message])
        server.sendmail(sender_email, [receiver_email], body)
        server.quit()
        print("Email notification sent")
    except:
        print("Error sending email notification")

# Change these values to your own email credentials and recipient email
sender_email = "your_email@gmail.com"
sender_password = "your_password"
receiver_email = "recipient_email@gmail.com"

while True:
    if check_internet_connection():
        print("Internet is connected")
    else:
        print("Internet connection not available")
        subject = "Internet Disconnected"
        message = "Please check your internet connection"
        send_email(subject, message, sender_email, sender_password, receiver_email)
    time.sleep(60)  # Check every minute
