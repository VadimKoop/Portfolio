#1. Leia päringuga aktiivsed kliendid, kes lubavad reklaami ja on aktiivse paketiga, mis maksab vähem kui 15€.
SELECT * FROM customers
LEFT JOIN contracts ON contracts.customer_id = customers.id
WHERE customers.ads_allowed = 1 AND contracts.is_active = 1 AND contracts.cost < 15;


#2. Leia päringuga töötaja ja tema poolt tehtud aktiivsete lepingute arv, koos aktiivsete klientide arvu ja pakettide müügi kogusummaga
SELECT employees.*, COUNT(contracts.id) AS Contracts_number, COUNT(customers.id) AS Customer_ID, SUM(contracts.cost) AS Total_Price FROM employees
LEFT JOIN contracts ON contracts.employee_id = employees.id
LEFT JOIN customers ON customers.id = contracts.customer_id
WHERE contracts.is_active = 1
GROUP BY employees.id;

#3. Leia päringuga iga töötaja puhul lõpetanud klientide arv ja keeleline jaotus.
SELECT employees.id as employee_id, CONCAT(employees.first_name, ' ', employees.last_name) as employee,
SUM(IF(contracts.is_active = 0, 1, 0)) as ended_contracts,
SUM(IF(customers.language = 'English', 1, 0)) as English,
SUM(IF(customers.language = 'Estonian', 1, 0)) as Estonian,
SUM(IF(customers.language = 'Russian', 1, 0)) as Russian
FROM employees
LEFT JOIN contracts
ON contracts.employee_id = employees.id
LEFT JOIN customers
ON customers.id = contracts.customer_id
GROUP BY employees.id;

#4. Lisa kõik tabelid üheks päringuks kokku, kasutades SQLi meetodeid nii, et ükski rida ei läheks kaotsi.
  select customer_name.*, contract.*, employee.*
  from contracts contract
  left join customers customer_name
  on customer_name.id = contract.customer_id
  left join employees employee
  on employee.id = contract.employee_id;
  
#5. Paku omalt poolt kaks huvitavat päringut ja selgita miks need võiksid huvi pakkuda.

Ma ei oska öelda, miks päring võiks huvitav olla, kuid võin Teile öelda paar huvitavat fakti SQL-i kohta.
1. SQL on rahvusvaheline standard. Seda tunnustavad ISO ja ANSI standard bodies.
2. SQL oli varem tuntud kui SEQUEL, mis tähendas Structured English Query Language.
Hiljem muudeti seda SQL-iks, kuna SEQUEL oli Suurbritannias asuva lennukifirma kaubamärk.