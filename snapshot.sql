--truncate table informes.Nacional
--truncate table informes.NacionalTally
--truncate table informes.NacionalProyectado

declare @date datetime
select @date=getdate()

insert into informes.Nacional(partido,nombre,votos,porciento,total_votos,total_inscritos,participacion,foto)
	execute dbo.spNacionalParcial 1
update informes.nacional set date=@date where date is null	

insert into informes.NacionalTally(reportadas,total,porciento)
	execute dbo.spNacionalTally 1
update informes.nacionaltally set date=@date where date is null		

insert into informes.NacionalProyectado(partido,nombre,votos,porciento,total_votos,total_inscritos,participacion,foto)
	execute dbo.spNacionalProyectado 1
update informes.nacionalproyectado set date=@date where date is null	

--select * from informes.nacional 
--inner join informes.nacionaltally on informes.nacional.date=informes.nacionaltally.date
--inner join informes.nacionalproyectado on informes.nacional.date=informes.nacionalproyectado.date and informes.nacional.partido=informes.nacionalproyectado.partido
