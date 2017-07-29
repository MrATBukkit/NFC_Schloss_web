#!/usr/bin/env python3
from py532lib.i2c import *
from py532lib.frame import *
from py532lib.constants import *
from py532lib.mifare import *
from time import sleep
from lcddriver import lcd

lcd = lcd()
lcd.display_string("Automatische", 1)
lcd.display_string("Erkennug", 2)
card = Mifare()
card.SAMconfigure()
card.set_max_retries(MIFARE_SAFE_RETRIES)
uid = card.scan_field()
serialnumber = ""
i = 0
while not uid and i < 80:
  uid = card.scan_field()
  sleep(0.25)
  i += 1
if uid:
  #print("uid: " + uid)   
  for x in range(0, len(uid)):
    serialnumber += ":" + hex(uid[x]).split('x')[1].zfill(2)
  print(serialnumber[1:].upper())
  lcd.display_string("Kehren Sie zur", 1)
  lcd.display_string("Webseite zurueck", 2)
  sleep(4)
else:
  print("Zeit überschritten")
  lcd.display_string("Zeit überschritten", 1)
  sleep(3)
  #os.system('sudo /etc/init.d/nfcschloss.sh stsat > /dev/null')
  #os.system('sudo python3 ./schloss.py&')
"""pn532 = Pn532_i2c()
pn532.SAMconfigure()

#print(pn532.read_response())

def getserialnumber():
  card_data = pn532.read_mifare().get_data()
  serialnumber = ""
  print(len(pn532.read_mifare().get_data()))
  #print("card: " + str(card_data[6]) + " len: " + str(len(card_data)))
  for x in range(0, len(card_data)):
    #print("X:" + str(x))
    #print(card_data[x])
    serialnumber += ":" + hex(card_data[x]).split('x')[1].zfill(2)
  return serialnumber[1:].upper()

try: 
  print(getserialnumber())
  #os.system('sudo /home/pi/NFC-Schloss/schloss.py &')
finally:
  pass"""
  #os.system('sudo /home/pi/NFC-Schloss/schloss.py &')
