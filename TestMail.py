import smtplib, ssl
from email.message import EmailMessage

port = 465  # For SSL
smtp_server = "smtp.gmail.com"
sender_email = "mitfahrerdb@gmail.com"  # Enter your address
receiver_email = ["jens.t2@gso.schule.koeln", "niklas.b42@gso.schule.koeln"]  # Enter receiver address
#ausführendes Gerät

#Windows-Computer
password = "fqosompobdatpbpv" 

#Mac
#password = "apcmcdggunpglljy" 

msg = EmailMessage()
msg.set_content("Hello TEST")
msg['Subject'] = "from Python Gmail!"
msg['From'] = sender_email
msg['To'] = receiver_email

context = ssl.create_default_context()
with smtplib.SMTP_SSL(smtp_server, port, context=context) as server:
    server.login(sender_email, password)
    server.send_message(msg, from_addr=sender_email, to_addrs=receiver_email)