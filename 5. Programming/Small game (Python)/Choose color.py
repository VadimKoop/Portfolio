from tkinter import *
root = Tk()

def colorR():
    fra.config(bg="Red")
def colorG():
    fra.config(bg="Green")
def colorB():
    fra.config(bg="Blue")

def square():
    fra.config(width=500)
    fra.config(height=500)
def rectangle():
    fra.config(width=700)
    fra.config(height=400)

fra = Frame(root,width=300,height=100,bg="Black")
fra.pack()
m = Menu(root)
root.config(menu=m)

cm = Menu(m)
m.add_cascade(label="Color",menu=cm)
cm.add_command(label="Red",command=colorR)
cm.add_command(label="Green",command=colorG)
cm.add_command(label="Blue",command=colorB)

sm = Menu(m)
m.add_cascade(label="Size",menu=sm)
sm.add_command(label="500x500",command=square)
sm.add_command(label="700x400",command=rectangle)

root.mainloop()
