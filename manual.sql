update resultados set votos=0
go

dbo.spImportData
go

dbo.spCircunscripcionProcess 1
go

dbo.spMunicipioProcess 1
go

dbo.spNacionalProcess 1
go

dbo.spProvinciaProcess 1
go