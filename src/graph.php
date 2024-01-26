<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
setInterval("if(looping) update();", 10000);
var looping = true;
var game;
function update()
{
	$.getJSON('loop.php?out=json', function(data){
		game = data;
		str = '<tr><td>'+game.date+'</td>';
		if(game.towns)
		{
			if(game.towns.Manchester
				&& game.towns.Manchester.commodities
				&& game.towns.Manchester.commodities.wool
				&& game.towns.Manchester.commodities.fabric)
			{
				str += '<td>'+game.towns.Manchester.commodities.wool.price+'</td><td>'+game.towns.Manchester.commodities.fabric.price+'</td>';
			}
			if(game.towns.Birmingham
				&& game.towns.Birmingham.commodities
				&& game.towns.Birmingham.commodities.wool
				&& game.towns.Birmingham.commodities.fabric)
			{
				str += '<td>'+game.towns.Birmingham.commodities.wool.price+'</td><td>'+game.towns.Birmingham.commodities.fabric.price+'</td>';
			}
		}
		str += '</tr>';
		$('#prices').append(str);
	});
}
update();
</script>
<style type="text/css">
.train_list{
	/*height: 80px;
	width: 520px;*/
	border: 1px solid #FFAAAA;
	background: #FFF0F0;
	margin: 3px;
	padding: 5px;
}
.town_list{
	width: 200px;
	border: 1px solid #AAAAFF;
	background: #F0F0FF;
	margin: 3px;
	padding: 5px;
}
</style>
<table id="prices">
 <tr><th>Date</th><th>Manchester - wool</th><th>Manchester - fabric</th><th>Birmingham - wool</th><th>Birmingham - fabric</th></tr>
</table>