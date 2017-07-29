#!/usr/bin/env python3
from py532lib.i2c import *
from py532lib.frame import *
from py532lib.constants import *
from py532lib.mifare import *
from time import sleep

card = Mifare()
card.SAMconfigure()
card.set_max_retries(MIFARE_SAFE_RETRIES)
uid = card.scan_field()
serialnumber = ""
while not uid:
  uid = card.scan_field() 
  sleep(0.25)
for x in range(0, len(uid)):
  serialnumber += ":" + hex(uid[x]).split('x')[1].zfill(2)
print(serialnumber[1:].upper())
