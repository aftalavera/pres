truncate table informes.Nacional
truncate table informes.Municipio
truncate table informes.Provincia


insert into informes.Nacional execute dbo.spNacionalParcial 1


declare @municipio int
set @municipio=1

while @municipio < 651
begin
	insert into informes.Municipio execute dbo.spMunicipio 1,@municipio
	set @municipio=@municipio+1
end

declare @provincia int
set @provincia=1

while @provincia < 33
begin
	insert into informes.Provincia execute dbo.spProvincia 1,@provincia
	set @provincia=@provincia+1
end

