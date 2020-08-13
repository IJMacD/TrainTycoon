SELECT 
	a.type,
	b.population / 1e6,
	f.demands,
	IFNULL(d.supplies,0),
	IFNULL(d.demands, 0),
	IFNULL(c.scale,0),
	a.supply_m,
	a.supply_c0,
	a.demand_m,
	a.demand_c0,
	c.scale,
	(
		(-SUM(IFNULL(d.supplies * c.scale, 0)) + (b.population / 1e6 * IFNULL(f.supplies,0)) + a.supply_c0) * a.demand_m -
		(SUM(IFNULL(d.demands * c.scale, 0)) + (b.population / 1e6 * IFNULL(f.demands, 0)) + a.demand_c0) * a.supply_m
	) 
	/ 
	( a.demand_m - a.supply_m ) AS price,
	IFNULL(e.available,0) AS available
FROM
	commodities2 AS a
	JOIN towns AS b
	LEFT JOIN buildings AS c
		ON c.town_id = b.id 
	LEFT JOIN production AS d
		ON c.`type` = d.`type` AND d.commodity = a.`type`
	LEFT JOIN availability AS e
		ON e.commodity = a.`type` AND e.town_id = b.id
	LEFT JOIN production AS  f
		ON f.`commodity` = a.`type` AND f.`type` = 'population'
WHERE b.id = 6
GROUP BY a.`type`