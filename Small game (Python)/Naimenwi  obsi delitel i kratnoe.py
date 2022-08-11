from tkinter import *
 
def gcd():
    n1,n2 = correct()
    prime1 = prime(n1)
    prime2 = prime(n2)
    gcd,lcm = gcd_lcm(n1,n2)
    lab_prime_n1["text"] = prime1
    lab_prime_n2["text"] = prime2
    lab_gcd["text"] = gcd
    lab_lcm["text"] = lcm
 
def correct():
    a = ent_n1.get()
    b = ent_n2.get()
    try:
        a = int(a)
    except ValueError:
        a = 0
        ent_n1.delete(0,END)
        ent_n1.insert(0,0)
    try:
        b = int(b)
    except ValueError:
        b = 0
        ent_n2.delete(0,END)
        ent_n2.insert(0,0)
    return abs(a), abs(b)
 
def prime(n):
    a = []
    while n > 1:
        i = 2
        while 1:
            if n%i==0:
                a.append(i)
                n //= i
                break
            else:
                i += 1
    return a
 
def gcd_lcm(a,b):
    m = a*b
    while a!=0 and b!=0:
        if a > b: a %= b
        else: b %= a
    g = a + b
    l = m // g
    return g, l
 
win = Tk()
win.title('НОД и НОК')
lab_n1 = Label(text="Число 1")
lab_n1.grid(row=2,column=0)
 
lab_n2 = Label(text="Число 2")
lab_n2.grid(row=3,column=0)
 
ent_n1 = Entry(width=10,bg="white")
ent_n1.grid(row=2,column=1)
 
ent_n2 = Entry(width=10,bg="white")
ent_n2.grid(row=3,column=1)
 
lab_prime = Label(text="Простые сомножители:")
lab_prime.grid(row=1,column=3,columnspan=2)
 
lab_prime_n1 = Label(bg="white")
lab_prime_n1.grid(row=2,column=3,columnspan=2)
 
lab_prime_n2 = Label(bg="white")
lab_prime_n2.grid(row=3,column=3,columnspan=2)
 
lab_gcd_ = Label(text="НОД",bg="lightgreen",width=7)
lab_gcd_.grid(row=4,column=3)
lab_gcd = Label(bg="lightgreen",width=10)
lab_gcd.grid(row=4,column=4)
 
lab_lcm_ = Label(text="НОК",bg="lightblue",width=7)
lab_lcm_.grid(row=5,column=3)
lab_lcm = Label(bg="lightblue",width=10)
lab_lcm.grid(row=5,column=4)
 
but = Button(text="Вычислить",command=gcd,pady=10)
but.grid(row=4,column=0,rowspan=2,columnspan=2,sticky=W+N+S+E)
 
win.mainloop()
