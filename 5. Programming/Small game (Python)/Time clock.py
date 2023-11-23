from tkinter import *
from math import *
import time
from random import randint

raam = Tk()
raam.title("Kell")
# tahvli laius
w = 500
# tahvli kõrgus
h = 500

tahvel = Canvas(raam, width=w, height=h, bg="white")

# kella raam
tahvel.create_oval(10,10,w-10,h-10)
# kella keskpunkt
tahvel.create_oval(w//2-5,h//2-5,w//2+5,h//2+5,fill="black")
sek_id = tahvel.create_line(w//2,h//2,w//2,20,fill="red")
tund_id = tahvel.create_line(w//2,h//2,w//2,50,fill="blue")

#def tundTipp(pos, pik):
 #   a = pik*cos(pi/2 - pos *  2 * pi)
  #  b = -pik * sin(pi/2 - pos * 2 * pi)
   # return a, b
    
def osutiTipp(positsioon, pikkus):
    """
    annab sekundiosuti liikuva tipu koordinaadid tavalises koordinaadistikus
    positsioon on ujukomaarv 0 ja 1 vahel
    """
    # arvutame x koordinaadi
    x = pikkus * cos(pi/2 - positsioon *  2 * pi)

    # arvutame y koordinaadi
    y = -pikkus * sin(pi/2 - positsioon * 2 * pi)

    # tagastame uued koordinaadid
    return x, y

def uuenda():
    # loeme jooksva sekundi
    sekundid = time.localtime().tm_sec
    

    # saame osuti liikuva tipu koordinaadid tavalises koordinaadistikus
    tipp_x, tipp_y  = osutiTipp(sekundid / 60, w // 2 - 20)
    

    # teisendame need canvas-i koordinaadistikku
    keskpunkt_x = w // 2
    keskpunkt_y = h // 2
    
    tipp_x = keskpunkt_x + tipp_x
    tipp_y = keskpunkt_y + tipp_y
    
    # uuendame osuti positsiooni
    tahvel.coords(sek_id, keskpunkt_x, keskpunkt_y, tipp_x, tipp_y)

    # ootame 1 sekundi ja siis uuendame kellaaega uuesti
    raam.after(1000, uuenda)


uuenda()
tahvel.pack()
raam.mainloop()
