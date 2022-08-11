from tkinter import *
from tkinter import ttk
from tkinter import messagebox

def tervita():
    tervitus = 'Tere ' + nimi.get()
    messagebox.showinfo(message=tervitus)


raam = Tk()
raam.title("Tervitaja")
# raam.geometry("300x100") # akna algne suurus määratakse vastavalt sisule


# paigutame sildi ruudustiku ülemisse vasakusse lahtrisse (column ja row)
# soovime, et sildi ümber jääks igas suunas 5 pikslit vaba ruumi (padx ja pady)
# soovime, et silt "kleepuks" oma lahtris ülemisse vasakusse nurka (sticky)
# N - north, W - west
silt = ttk.Label(raam, text="Nimi")
silt.grid(column=0, row=0, padx=5, pady=5, sticky=(N, W))

# tekstikasti puhul soovime, et ta kleepuks nii ida- kui lääneserva külge
# st. ta peab venima vastavalt akna suurusele
nimi = ttk.Entry(raam)
nimi.grid(column=1, row=0, padx=5, pady=5, sticky=(N, W, E))

# soovime, et nupp veniks nii laiuses kui ka kõrguses
nupp = ttk.Button(raam, text="Tervita!", command=tervita)
nupp.grid(column=1, row=1, padx=5, pady=5, sticky=(N, S, W, E))

# soovime, et akna suuruse muutmisel muudetakse veeru 1 ja rea 1 mõõtmeid
# (st. veerg 0 ja rida 0 jäävad sama laiaks/kõrgeks)
raam.columnconfigure(1, weight=1)
raam.rowconfigure(1, weight=1)

# kuvame akna ekraanile
raam.mainloop()
