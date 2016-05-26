truncate table informes.Nacional
truncate table informes.Municipio
truncate table informes.Provincia


insert into informes.Nacional execute dbo.spNacionalProyectado 1 

declare @municipio int
set @municipio=1

while @municipio < 651
begin
	insert into informes.Municipio(partido,nombre,votos,porciento,total_votos,total_inscritos,participacion,foto) execute dbo.spMunicipioProyectado 1,@municipio
	update informes.Municipio set municipio=municipios.nombre from municipios where municipios.cod_muni=@municipio and municipio is null
	set @municipio=@municipio+1
end

declare @provincia int
set @provincia=1

while @provincia < 33
begin
	insert into informes.Provincia(partido,nombre,votos,porciento,total_votos,total_inscritos,participacion,foto) execute dbo.spProvinciaProyectado 1,@provincia
	update informes.Provincia set provincia=provincias.nombre from provincias where provincias.cod_prov=@provincia and provincia is null
	set @provincia=@provincia+1
end

