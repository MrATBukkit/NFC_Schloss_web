from lcddriver import lcd
from time import time
from time import sleep
from datetime import datetime
import threading

lcd = lcd()

class Uhr(threading.Thread):
  def __init__(self):
    threading.Thread.__init__(self)
    self.stop = False
    self.pause = False
  def run(self):
    while not self.stop:
      if not self.pause:
        dateString = datetime.now().strftime('%b %d %y')
        timeString = datetime.now().strftime('%H:%M:%S')
        lcd.display_string(dateString, 1)
        lcd.display_string(timeString, 2)
        sleep(1)
      else:
        sleep(1)

th = Uhr()
th.start()
x = input("Warte")
th.stop = True
th.join()
print("Ende")
