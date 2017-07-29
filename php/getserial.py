#!/usr/bin/env python3
from py532lib.i2c import *
from py532lib.frame import *
from py532lib.constants import *
import os

pn532 = Pn532_i2c()
pn532.SAMconfigure()

def getserialnumber():
  card_data = pn532.read_mifare().get_data()
  serialnumber = ""
  for x in range(len(card_data)-7, len(card_data)):
    serialnumber += ":" + hex(card_data[x]).split('x')[1].zfill(2)
  return serialnumber[1:].upper()

try: 
  print(getserialnumber())
except Exception:
  os.system('sudo /home/pi/NFC-Schloss/schloss.py')
