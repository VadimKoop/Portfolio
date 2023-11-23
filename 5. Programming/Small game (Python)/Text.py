from tkinter import *
tx = Text(font=('times',12),width=50,height=15,wrap=WORD)
tx.pack(expand=YES,fill=BOTH)
tx.insert(1.0,'Дзэн Питона\n\
Если интерпретатору Питона дать команду\n\
import this ("импортировать это"),\n\
то выведется так называемый "Дзен Питона".\n Некоторые выражения:\n\
* Если реализацию сложно объяснить — это плохая идея.\n\
* Ошибки никогда не должны замалчиваться.\n\
* Явное лучше неявного.\n\n') 
#установка тегов для областей текста
tx.tag_add('title','1.0','1.end')
tx.tag_add('special','6.0','8.end')
tx.tag_add('special','3.0','3.11')
 
#конфигурирование тегов
tx.tag_config('title',foreground='red',
          font=('times',14,'underline'),justify=CENTER)
tx.tag_config('special',background='grey85',font=('Dejavu',10,'bold'))
