#!/usr/bin/env python3
from lcddriver import lcd 
from time import time 
from time import sleep 
from py532lib.i2c import *
from py532lib.frame import *
from py532lib.constants import *
from py532lib.mifare import *
#from __future__ import print_function
import pymysql
import RPi.GPIO as GPIO
 
lcd = lcd()
GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)
GPIO.setup(15, GPIO.OUT)
GPIO.output(15, GPIO.LOW)

def lcdoutput(text, time=10):
  lcd.display_string("                ", 1)
  lcd.display_string("                ", 2)
  if len(text) > 16:
    line1 = text[:17]
    lcd.display_string(line1, 1)
    line2 = text[16:].strip()
    if len(line2) >= 16:
      for x in range(0, 2):
        for i in range(0, len(line2)+15):
          leer = " "
          if i < 16:
            faktor = 16 - i
            leer *= faktor
            ausgabe = leer + line2[0:i]
          else:
            ausgabe = line2[i-15:i]
            ausgabe += "                "
          lcd.display_string(ausgabe, 2)
          sleep(0.20)
    else:
      lcd.display_string(line2, 2)
      sleep(time)
  else:
    lcd.display_string(text, 1)
    sleep(time)

pn532 = Pn532_i2c()
pn532.SAMconfigure()

card = Mifare()
card.SAMconfigure()
card.set_max_retries(MIFARE_SAFE_RETRIES)

def getserialnumber():
  uid = card.scan_field()
  serialnumber = ""
  while not uid:
    uid = card.scan_field()
    sleep(0.25)
  for x in range(0, len(uid)):
    serialnumber += ":" + hex(uid[x]).split('x')[1].zfill(2)
  return(serialnumber[1:].upper())
			 
try:
  while True:
    lcdoutput("Warte auf Chip", 0)
    serialnumber = getserialnumber()
    db = pymysql.connect(host="localhost",
                         user="root",
                         passwd="TH19-17",
                         db="NFC_Schloss")
    cur = db.cursor()
    cur.execute("SELECT * FROM userkey WHERE keyid='" + serialnumber+"'")
    result = cur.fetchone()
    if (result == "None" or result == None):
      lcdoutput("Sie sind nicht  autorisiert")
    else:
      cur.execute("SELECT * FROM user WHERE id='" + str(result[3]) +"'")
      result = cur.fetchone()
      if (result == "None" or result == None):
        lcdoutput("Fehler in der Datenbank kontaktieren sie den Administrator", 15)
      else:
        GPIO.output(15, GPIO.HIGH)
        sleep(0.5)
        #lcdoutput("Guten Tag " + result[2] + " " + result[3])
        GPIO.output(15, GPIO.LOW)
        lcdoutput("Guten Tag " + result[2] + " " + result[3])
    db.close()
except KeyboardInterrupt:
  lcdoutput("Programm wurde Beendet", 1.5)
finally:
  lcdoutput("Administrator Kontaktieren",0)
