# impordi tk vidinad ja konstandid
from tkinter import *
from tkinter import ttk
from tkinter import messagebox

# see funktsioon käivitatakse nupule klõpsamisel
def tervita():
    tervitus = 'Tere ' + nimi.get() + perekonnanimi.get() +'!'
    messagebox.showinfo(message=tervitus)


# loome akna
raam = Tk()
raam.title("Tervitaja")
raam.geometry("300x100")

# loome tekstikasti jaoks sildi
silt = ttk.Label(raam, text="Nimi")
silt.place(x=5, y=5)

perekonnanimi = ttk.Label(raam, text="Perekonnanimi")
perekonnanimi.place(x=5, y=25)
perekonnanimi = ttk.Entry(raam)
perekonnanimi.place(x=70, y=25, width=150)

# loome tekstikasti
nimi = ttk.Entry(raam)
nimi.place(x=70, y=5, width=150)

# loome nupu
nupp = ttk.Button(raam, text="Tervita!", command=tervita)
nupp.place(x=70, y=40, width=150)

# ilmutame akna ekraanile
raam.mainloop()
