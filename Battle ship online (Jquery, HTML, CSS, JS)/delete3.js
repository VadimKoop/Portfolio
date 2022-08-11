
// Создаю глобальные переменные и глобальные массивы
var u = 10; 
var counter = 0; 

var timer;



var arrPlayer=[]; // Хранят все ID полей
var arrComputer=[];// Хранят все ID полей
var stringItemss;   

var compShips=[];  // Хранят ИД только корбалей
var playerShips=[];

var compShots=[];  // Из этого массива выбираю куда компьютер уже стрельнул

var compshotss= 0;  // Подсчет ударов
var playershots = 0;

var secs = 0;

 var elementSize = document.getElementById('boardSize');  // Переменн. со значение размера поля
  var elementNumber = document.getElementById('boatNumber'); // Перемен.. сколько выбрано кораблей для игры

function getDateTime() {
    var now     = new Date(); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    var hour    = now.getHours();
    var minute  = now.getMinutes();
    var second  = now.getSeconds(); 
    if(month.toString().length == 1) {
        var month = '0'+month;
    }
    if(day.toString().length == 1) {
        var day = '0'+day;
    }   
    if(hour.toString().length == 1) {
        var hour = '0'+hour;
    }
    if(minute.toString().length == 1) {
        var minute = '0'+minute;
    }
    if(second.toString().length == 1) {
        var second = '0'+second;
    }   
    var dateTime = year+'/'+month+'/'+day+'x'+hour+':'+minute+':'+second;   
     return dateTime;
}
function writeFile() {
	 var formData = new FormData(document.forms.writeInFile);

  // добавить к пересылке ещё пару ключ - значение
 formData.append("patronym", "Робертович");
 console.log("WRITING IN FILE...");
  // отослать
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "http://dijkstra.cs.ttu.ee/~Valentin.Popov/cgi-bin/prax3/write.py");
  xhr.send(formData);
  
}


 function getTable () {
 	  var name = document.getElementById("playerName");
 	  window.location = "http://dijkstra.cs.ttu.ee/~Valentin.Popov/cgi-bin/prax3/form.py"; 
 }
 function setName() {

   console.log("stavlju imja");
   var name = document.getElementById("playerName");
   console.log("NAME: "+name.value.split(" ").join());
   if (name.value=="") {
   	document.getElementById('playerNameId').value = "Anonym";
   }
   else {
   	var namy = name.value.split(" ").join();
   	document.getElementById('playerNameId').value = namy;
   }
   
   document.getElementById("preview2").style.visibility = "hidden";
}


     	function start_timer()
    {
        
        console.log("TIME:   "+secs);
    if (timer) clearInterval(timer);
    secs = 0;
    document.getElementById('timer').innerHTML = 'Timer: '+ secs + ' sec.';
     timer = setInterval(
        function () {
         secs++;
         document.getElementById('timer').innerHTML = 'Timer:'+ secs + ' sec';
         },
         1000
        );
         }

     // У каждого квадратика свой ИД!


	
function playerMove(el){
	  
	  // Когда ты нажимаешь то на элементе запускается эта функция, он берет ИД куда ты нажал и проверяет есть ли он в каких нибудь массивах и смотря на это он ставит картинку на задний фон ( CSS ) попал ли ты или мимо , также он проверяет не сбил ли ты уже все корабли и заканчивает игру (44 строчка)
	 
	  
	playershots++;
  var g = $(el).attr("id");
    var gs = document.getElementById(g);
    
   
   if($.inArray($(el).attr("id"), compShips)!=-1){
    	
     gs.setAttribute("style","background-image: url(boat2.jpg)");
     
      var deleteold2 = compShips.indexOf($(el).attr("id"));
         if(deleteold2 != -1) {
        compShips.splice(deleteold2, 1);
        
         }
         if(compShips.length==0){
         	
            document.getElementById('playerScoreId').value = playershots;
            document.getElementById('player2ScoreId').value = compshotss;
            document.getElementById('timeId').value = secs;
            document.getElementById('dataId').value = getDateTime();    
     	
         	writeFile();
         	
         	alert('Congratulations you won!'+' Shots PLAYER: '+playershots+'   Shots COMP: '+compshotss+' Time: '+secs+" sec");
         	
         	if(counter==10){
         	$('#scoreboard').find('p').first().remove();
         	  //$( "#scoreboard" ).empty();
         	}
         	var scored  =  document.getElementById('scoreboard');
         	scored.innerHTML +='<p> Time: '+secs+' -- Board Size: '+elementSize.value+'x'+elementSize.value+' -- Ships: '+elementNumber.value+' -- Player shots: '+playershots+' -- Computer shots: '+compshotss+' -- WIN '+'</p>';
         	playershots=0;
         	compshotss=0;
         	if(counter!=3){
         	counter++;
         }
         	
           createBoard();	
	  }  
         
     }
     
     else{
     	gs.setAttribute("style", "background-image: url(watermiss.jpg)");
      computerMove();     	
     	
    }
    

}	

function computerMove(){
   
   compshotss++;   	
	var shot  = compShots[Math.floor(Math.random()*compShots.length)];  // Генерирует случайный ИД из массива из ИД который сгенерировали ниже ! а дальше действует как верхняя функция
	var gs = shot;
	
	 var deleteold = compShots.indexOf(shot);
         if(deleteold != -1) {
        compShots.splice(deleteold, 1);
         }
    
 	var ls = gs.toString();
	
	
	var gs = document.getElementById(ls);
	if(($.inArray(ls, playerShips))!=-1){
		// Сдесь можешь показаться запутанно просто у меня тут постоянные преобразования из Стринг в Инт  и тд.
      console.log("stavlju style");
      gs.setAttribute("style", "background-image: url(boat2.jpg)");
       var deleteold2 = ls.charAt(0)+ls.charAt(1);
       var deleteold3 = playerShips.indexOf(deleteold2);
        if(deleteold3 != -1) {
        playerShips.splice(deleteold3, 1);
         }
         console.log(playerShips+' PLAYERSHIPS  ')
          if(playerShips.length==0){
          	document.getElementById('playerScoreId').value = playershots;
            document.getElementById('player2ScoreId').value = compshotss;
            document.getElementById('timeId').value = secs; 
          	writeFile();
         	alert('Sorry u lose!'+' Shots PLAYER: '+playershots+'   Shots COMP: '+compshotss+' Time: '+secs+" secs");
         	if(counter==10){
         	$('#scoreboard').find('p').first().remove();
         	 
         	}
         	var scored  =  document.getElementById('scoreboard');
         	scored.innerHTML +='<p> Time: '+secs+' -- Board Size: '+elementSize.value+'x'+elementSize.value+' --Ships: '+elementNumber.value+' -- Player shots: '+playershots+' -- Computer shots: '+compshotss+' -- LOSE '+'</p>';
         	playershots=0;
         	compshotss=0;
         	if(counter!=10){
         	counter++;
         }
            createBoard();	 // Функция ниже описана
	       }  
     
     }
     
    else {
         console.log("mimo");
     gs.setAttribute("style", "background-image: url(watermiss.jpg)");
    
    }
    
   
     
     console.log('ls '+ls+' Playerships '+playerShips+' compShots '+compShots);
	//$("gs").click();
	
		


}	
	

 function createBoard(){
 	
 	// Это функция которая запускается при нажатии кнопки старт 
 	
 	console.log('start'); // это чисто для ебя в консоле)
    
    start_timer()
 	// Делаю все массивы пустыми , чтобы можно было каждые раз нажав на кнопку играть заного в новую игру 
 	arrPlayer =[];
 	arrComputer =[];
 	compShips=[];
   playerShips=[];
   compShots=[];
 	// Удаляю старые таблицы после предудыщей игры
 	$("#computerTable").children().remove();
 	$("#playerTable").children().remove();
 	
 	
   elementSize = document.getElementById('boardSize');
   elementNumber = document.getElementById('boatNumber');
  
  var sizeValue = elementSize.value;
  var numberValue = elementNumber.value;
  
  // Мелкие проверки
  var tables2 = document.getElementById("computerTable");
   var tdCounter2 = '';
   
  if(elementSize.value==10){
     sizeValue--;
     if(elementNumber.value==9){
     numberValue--;  
     }
    } 
    
   var uniqueID2=11;
   var uniqueSizeS = 0;
   var uniqueSizeSS = sizeValue;
  
 // Computer table  
  // Создаю поля для компьютера. Берется sizeValue(допустим мы выбираем 5х5 то в хтмл стоит значение для этого  5. 146 строчка в index.html, и делаю Стринг из 5 строчек для таблицы нарпирмер 5 раз)
 for(var i=0; i<sizeValue;i++){
    tdCounter2 += '<td class=g onclick= playerMove(this) id=666></td>';
   
  }
  // Потом 5 раз делаю добавляю в таблицу эти 5 строчек и получается 5х5 поляна
  for(var i=0; i<sizeValue;i++){
    tables2.innerHTML += '<tr>'+tdCounter2;
    for(var j=0; j<sizeValue;j++ ){
    	// Добавляю в массив ИД каждого квадратика через циклы 
     document.getElementById("666").setAttribute("id", uniqueID2+'s');
     arrComputer.push(uniqueID2+'s');
     
     
     uniqueSizeS=uniqueID2%10;
     uniqueID2++;
     if(uniqueSizeS==uniqueSizeSS ){
      uniqueID2+=10-sizeValue;
      }
    }
   }  
    
   // Player table
   // Сдвесь все работает так же как и для компьютера. Только посмотри для себя какие ИД у квадратиков чтоб понять что они все разные  
   
   var tables = document.getElementById("playerTable");
   var tdCounter = '';
   var uniqueID=11;
   var uniqueSize = 0;
   var uniqueSize2 = sizeValue;
   
  for(var i=0; i<sizeValue;i++){
    tdCounter += '<td class=g id=777></td>';
  }
  for(var i=0; i<sizeValue;i++){
    tables.innerHTML += '<tr>'+tdCounter;
    for(var j=0; j<sizeValue;j++ ){
     document.getElementById("777").setAttribute("id", uniqueID);
     //document.getElementById("777").setAttribute("id", uniqueID);
     arrPlayer.push(uniqueID);
     compShots.push(uniqueID);
     uniqueSize=uniqueID%10;
     uniqueID++;
     
     //uniqueSize=uniqueID%10;
     
     if(uniqueSize==uniqueSize2 ){
      uniqueID+=10-sizeValue;
      
     }
     
    
    }
   }

   
   // check for 3x    player table
   // Сдесь хитрая проверка если у еня 3х3 поляна , то я из массивов удаляю вторую строчку чтобы он туда корабли не ставил, а то если он ставит на 2 строчку то потмо на другие по правилам не может и Эррор вылетает
   
    if(sizeValue==3){
     var check3x = arrPlayer.indexOf(22);
         if(check3x != -1) {
         	
	      arrPlayer.splice(check3x, 1);
         }
         var check3x1 = arrPlayer.indexOf(21);
         if(check3x1 != -1) {
         	
	      arrPlayer.splice(check3x1, 1);
         }
        var check3x1 = arrPlayer.indexOf(23);
         if(check3x1 != -1) {
         	
	      arrPlayer.splice(check3x1, 1);
         }
         	
         //---- comp table check
           var checkcheck = arrComputer.indexOf(22+'s');
         if(checkcheck != -1) {
         
	      arrComputer.splice(checkcheck, 1);
         }
         var checkcheck1 = arrComputer.indexOf(21+'s');
         if(checkcheck1 != -1) {
         	
	      arrComputer.splice(checkcheck1, 1);
         }
         var checkcheck2 = arrComputer.indexOf(23+'s');
         if(checkcheck2 != -1) {
         	
	      arrComputer.splice(checkcheck2, 1);
         }
      }
   
  // Rastanovka koroblej
 // с 300 по 650 строчки это идут одни проверки на расстановки, у меня поле 11 12 13
                                                                         // 21 22 23
                                                                         // 31 32 33 
   // Я генерирую сначала один кораблик напрмер с ИД 11 и после этого я удаляю те ИД где нельзя строить корабли 21 22 13  по правилам которые на сайте
 
   var shiper;
   var shiperS;
   var shiper2;
   var shiperS2;
   
   var boat = parseInt(numberValue);
   var size77 = parseInt(sizeValue);
   // Check boatNumber
    if( boat > size77 ){
     alert("boatNumber has to be < than boardSize");
     document.getElementById("boatNumber").selectedIndex = 0;
      return;
    
    }   
    
     if(	boat == size77){
     alert("boatNumber has to be <RAVNO than boardSize");
     document.getElementById("boatNumber").selectedIndex = 0;
      return;
    }  
    

      
    for(var n=0; n<numberValue;n++) {
    
    var first,second,third,firstss,secondss,thirdss;	
    
   
    function placement() {	
     
 	 //player shipp placement
      var item = arrPlayer[Math.floor(Math.random()*arrPlayer.length)];
      //generateo for comp//
     //------
      var stringItem = item.toString();
      
      	
      first =  stringItem.charAt(0);
      second = stringItem.charAt(1);
   
      third = 0;
      var parser = parseInt(second);
      if(parser<sizeValue){
      third = second;
      third++;
      }
      if(parser>=sizeValue){
      third = second;
      third--;
      }
       if(parser==2){
      third = second;
      third--;
      }
      
      
      var xz= (""+first)+third;
      var secondboat = parseInt(xz);
      
      var delete0 = item;
      delete0 +=2;
      var delete1 = item;
      delete1 -=2;
      var delete2 = item;
      delete2 +=10;
      var delete3 = item;
      delete3 -=10;
      
      var delete4 = secondboat;
      delete4 +=2;
      var delete5 = secondboat;
      delete5 -=2;
      var delete6 = secondboat;
      delete6 +=10;
      var delete7 = secondboat;
      delete7 -=10;
      
      var delete8 = item;
      delete8 +=9;
      var delete9 = item;
      delete9 -=9;
      var delete10 = item;
      delete10 +=11;
      var delete11 = item;
      delete11 -=11;
      
      var delete12 = secondboat;
      delete12 +=9;
      var delete13 = secondboat;
      delete13 -=9;
      var delete14 = secondboat;
      delete14 +=11;
      var delete15 = secondboat;
      delete15 -=11;
      
     
      
      
         var gg0 = arrPlayer.indexOf(delete0);
         if(gg0 != -1) {
         arrPlayer.splice(gg0, 1);
         }
         
         
         var gg1 = arrPlayer.indexOf(delete1);
         if(gg1 != -1) {
         arrPlayer.splice(gg1, 1);
         }
         
         
         var gg2 = arrPlayer.indexOf(delete2);
         if(gg2 != -1) {
         arrPlayer.splice(gg2, 1);
         }
         
         
         var gg3 = arrPlayer.indexOf(delete3);
         if(gg3 != -1) {
        arrPlayer.splice(gg3, 1);
         }
      	
      	 var gg4 = arrPlayer.indexOf(delete4);
         if(gg4 != -1) {
         	
	      arrPlayer.splice(gg4, 1);
         }
         
         var gg5 = arrPlayer.indexOf(delete5);
         if(gg5 != -1) {
         arrPlayer.splice(gg5, 1);
         }
         
         
         var gg6 = arrPlayer.indexOf(delete6);
         if(gg6 != -1) {
         arrPlayer.splice(gg6, 1);
         }
           var gg7 = arrPlayer.indexOf(delete7);
         if(gg7 != -1) {
         	
	      arrPlayer.splice(gg7, 1);
         }
         
         var gg8 = arrPlayer.indexOf(delete8);
         if(gg8 != -1) {
         arrPlayer.splice(gg8, 1);
         }
         
          var gg9 = arrPlayer.indexOf(delete9);
         if(gg9 != -1) {
         arrPlayer.splice(gg9, 1);
         }
         
          var gg10 = arrPlayer.indexOf(delete10);
         if(gg10 != -1) {
         arrPlayer.splice(gg10, 1);
         }
         
          var gg11 = arrPlayer.indexOf(delete11);
         if(gg11 != -1) {
         arrPlayer.splice(gg11, 1);
         }
         
           var gg12 = arrPlayer.indexOf(delete12);
         if(gg12 != -1) {
         arrPlayer.splice(gg12, 1);
         }
         
           var gg13 = arrPlayer.indexOf(delete13);
         if(gg13 != -1) {
         arrPlayer.splice(gg13, 1);
         }
         
           var gg14 = arrPlayer.indexOf(delete14);
         if(gg14 != -1) {
         arrPlayer.splice(gg14, 1);
         }
         
           var gg15 = arrPlayer.indexOf(delete15);
         if(gg15 != -1) {
       arrPlayer.splice(gg15, 1);
         }
         
         var me = arrPlayer.indexOf(item);
         if(me != -1) {
         arrPlayer.splice(me, 1);
         }
         var me2 = arrPlayer.indexOf(secondboat);
         if(me2 != -1) {
         arrPlayer.splice(me2, 1);
         }
         
         // Computer SHip placement
         
     var itemss3;
      var itemss3 = arrComputer[Math.floor(Math.random()*arrComputer.length)];
      
     
      var itemms5 = itemss3.charAt(0)+itemss3.charAt(1)
      var itemms4 = parseInt(itemms5);
      
      
     
      stringItemss = itemms4.toString();
      
      firstss =  stringItemss.charAt(0);
      secondss = stringItemss.charAt(1);
      
      thirdss = 0;
      var parserss = parseInt(secondss);
      if(parserss<sizeValue){
      thirdss = secondss;
      thirdss++;
      }
      if(parserss>=sizeValue){
      thirdss = secondss;
      thirdss--;
      }
       if(parserss==2){
      thirdss = secondss;
      thirdss--;
      }
      
     var xzss= (""+firstss)+thirdss;
      var secondboatss = parseInt(xzss);
     
      var delete0ss = itemms4;
      delete0ss +=2;
      var delete1ss = itemms4;
      delete1ss -=2;
      var delete2ss = itemms4;
      delete2ss +=10;
      var delete3ss = itemms4;
      delete3ss -=10;
      
      var delete4ss = secondboatss;
      delete4ss +=2;
      var delete5ss = secondboatss;
      delete5ss -=2;
      var delete6ss = secondboatss;
      delete6ss +=10;
      var delete7ss = secondboatss;
      delete7ss -=10;
      
      var delete8ss = itemms4;
      delete8ss +=9;
     
      var delete9ss = itemms4;
      delete9ss -=9;
      var delete10ss = itemms4;
      delete10ss +=11;
      var delete11ss = itemms4;
      delete11ss -=11;
      
      var delete12ss = secondboatss;
      delete12ss +=9;
      var delete13ss = secondboatss;
      delete13ss -=9;
      var delete14ss = secondboatss;
      delete14ss +=11;
      var delete15ss = secondboatss;
      delete15ss -=11;
      
     
      
         var gg0ss = arrComputer.indexOf(delete0ss+'s');
         if(gg0ss != -1) {
         
	      arrComputer.splice(gg0ss, 1);
         }
         
         
         var gg1ss = arrComputer.indexOf(delete1ss+'s');
         if(gg1ss != -1) {
         	
	      arrComputer.splice(gg1ss, 1);
         }
         
         
         var gg2ss = arrComputer.indexOf(delete2ss+'s');
         if(gg2ss != -1) {

	      arrComputer.splice(gg2ss, 1);
         }
         
         
         var gg3ss = arrComputer.indexOf(delete3ss+'s');
         if(gg3ss != -1) {

	      arrComputer.splice(gg3ss, 1);
         }
      	
      	
      	 var gg4ss = arrComputer.indexOf(delete4ss+'s');
         if(gg4ss != -1) {

	      arrComputer.splice(gg4ss, 1);
         }
         
         
         var gg5ss = arrComputer.indexOf(delete5ss+'s');
         if(gg5ss != -1) {

	      arrComputer.splice(gg5ss, 1);
         }
         
         
         var gg6ss = arrComputer.indexOf(delete6ss+'s');
         if(gg6ss != -1) {

	      arrComputer.splice(gg6ss, 1);
         }
           var gg7ss = arrComputer.indexOf(delete7ss+'s');
         if(gg7ss != -1) {

	      arrComputer.splice(gg7ss, 1);
         }
         
         
         var gg8ss = arrComputer.indexOf(delete8ss+'s');
         if(gg8ss != -1) {

	      arrComputer.splice(gg8ss, 1);
         }
         
          var gg9ss = arrComputer.indexOf(delete9ss+'s');
         if(gg9ss != -1) {

	      arrComputer.splice(gg9ss, 1);
         }
         
          var gg10ss = arrComputer.indexOf(delete10ss+'s');
         if(gg10ss != -1) {

	      arrComputer.splice(gg10ss, 1);
         }
         
          var gg11ss = arrComputer.indexOf(delete11ss+'s');
         if(gg11ss != -1) {

	      arrComputer.splice(gg11ss, 1);
         }
         
           var gg12ss = arrComputer.indexOf(delete12ss+'s');
         if(gg12ss != -1) {

	      arrComputer.splice(gg12ss, 1);
         }
         
           var gg13ss = arrComputer.indexOf(delete13ss+'s');
         if(gg13ss != -1) {

	      arrComputer.splice(gg13ss, 1);
         }
         
           var gg14ss = arrComputer.indexOf(delete14ss+'s');
         if(gg14ss != -1) {

	      arrComputer.splice(gg14ss, 1);
         }
         
           var gg15ss = arrComputer.indexOf(delete15ss+'s');
         if(gg15ss != -1) {

	      arrComputer.splice(gg15ss, 1);
         }
         
         var mess = arrComputer.indexOf(itemms4+'s');
         if(mess != -1) {

	      arrComputer.splice(mess, 1);
         }
         
         
         var me2ss = arrComputer.indexOf(secondboatss+'s');
         if(me2ss != -1) {

	      arrComputer.splice(me2ss, 1);
         }
         
      }
      placement();
      // Сдесь проверки закончены и я добавляю все на поляну
    // player ships
      shiper = document.getElementById((""+first)+second);
      shiperS = document.getElementById((""+first)+third);
      
    // comp ships
      shiper2 = document.getElementById((""+firstss)+secondss+'s');
      shiperS2 = document.getElementById((""+firstss)+thirdss+'s');
      
      playerShips.push((""+first)+second);  // push Это команда для добавление в массив и я заполняю массивы где и как стоят корабли, кидаю туда ИД
      playerShips.push((""+first)+third);
      
      compShips.push((""+firstss)+secondss+'s');
      compShips.push((""+firstss)+thirdss+'s');
      
      
      console.log("PLAYER "+playerShips);
      console.log('COMP '+compShips);
   
      shiper.setAttribute("style", "background-image: url(boat.jpg)");  // Сдесь просто ставятся картинки на места где стоят корабли, а картинки воды ставятся в CSS и стоят для класса G (22 строка в index.html ) просто все кубики они в классе G но ИД у них у всех разные!
      shiperS.setAttribute("style", "background-image: url(boat.jpg)");
      
     // shiper2.innerHTML="*";
     // shiperS2.innerHTML="*";
     
  
   }
   
   
 }


     
