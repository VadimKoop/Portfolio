from tkinter import *
win = Tk()
win.title("Решение примеров")
 
def test(e):
    try:
        lab2["text"] = eval(ent.get())
    except:
        lab2["text"] = "Выражение введено с ошибкой"
 
 
lab = Label(text="Введите математическое выражение из чисел и знаков +, -, *, /, //, %, **")
lab.pack()
ent = Entry(width=50,bg="white",justify=CENTER)
ent.focus()
ent.pack()
ent.bind('<Return>', test)
lab2 = Label()
lab2.pack()
 
win.mainloop()
