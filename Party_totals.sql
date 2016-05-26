declare @source int=1


WITH ColegiosWithTotals(cod_muni,colegio,inscritos,total) AS 
(
	SELECT r.cod_muni,r.colegio,c.inscritos,sum(r.votos)
	FROM Resultados r 
	INNER JOIN Colegios c on r.cod_muni=c.cod_muni and r.colegio=c.colegio
	where r.source=@source
	group by r.cod_muni,r.colegio,c.inscritos
	having sum(r.votos)>0
),

Acumulados (total_inscritos,total_votos) as
(
	select cast(sum(inscritos) as real),cast(sum(total) as real) from ColegiosWithTotals
),

Totales(partido,votos) as 
(
	SELECT r.partido,SUM(r.votos)
	FROM Resultados r 
	INNER JOIN ColegiosWithTotals c on c.cod_muni=r.cod_muni and c.colegio=r.colegio 
	where r.source=@source
	group by r.partido
)

SELECT partido, t.votos as votos, t.votos/total_votos as porciento, total_votos, total_inscritos,total_votos/total_inscritos participacion
FROM totales t 
LEFT JOIN Candidatos c on c.cod_part=t.partido,
Acumulados
order by t.votos desc









