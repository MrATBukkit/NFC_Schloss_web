#!/usr/bin/env python3
import os
import subprocess
import re
from lcddriver import lcd

def findThisProcess( process_name ):
  ps     = subprocess.Popen("ps -eaf | grep "+process_name, shell=True, stdout=subprocess.PIPE)
  output = ps.stdout.read()
  ps.stdout.close()
  ps.wait()

  return output

# This is the function you can use  
def isThisRunning( process_name ):
  output = findThisProcess( process_name )

  if re.search('/etc/init.d/'+process_name, output) is None:
    return False
  else:
    return True

# Example of how to use
if isThisRunning('nfcschloss.sh') == False:
  print("Not running")
else:
  print("Running!")
