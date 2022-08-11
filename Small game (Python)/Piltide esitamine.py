from tkinter import *

raam = Tk()
raam.title("Tahvel")
tahvel = Canvas(raam, width=600, height=600, background="white")
tahvel.grid()

# pildi kuvamisel on vaja kõigepealt laadida pilt ja panna see siis tahvlile
pall = PhotoImage(file="pall.gif")
img = tahvel.create_image(450, 80, image=pall)

# activeimage määrab pildi, mida näidatakse, kui hiirekursor on pildi kohal
# anchor näitab, mille järgi pilt paigutatakse (antud juhul ülemise-vasaku nurga järgi)
suletud = PhotoImage(file="suletud.gif")
avatud = PhotoImage(file="avatud.gif")
img = tahvel.create_image(50, 400, image=suletud, activeimage=avatud, anchor=NW)

raam.mainloop()
