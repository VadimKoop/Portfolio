from tkinter import *
from math import sin

raam = Tk()

w = 500 # tahvli laius
h = 500 # tahvli pikkus
tahvel = Canvas(raam, width=w, height=h, background="white")
tahvel.grid()

# vertikaalne telg
tahvel.create_line(0, h/2, 0, -h/2, arrow=LAST)
# horisontaalne telg
tahvel.create_line(-w/2, 0, w/2, 0, arrow=LAST)

punktid = []
# genereerime graafiku punktid kujul [x0,f(x0), x1,f(x1),..., xn, f(xn)]
for x in range(w // -2, w // 2):
    suurendus = 30
    punktid.append(x)
    y = sin(x / suurendus)
    punktid.append(y * suurendus)

# joonistame graafiku (anname argumendid järjendina)
tahvel.create_line(punktid, fill="red")

# nihutame kõik objektid 250px võrra paremale ja alla
tahvel.move(ALL, w/2, h/2)

raam.mainloop()
