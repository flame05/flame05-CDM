$('.cmdMod').click(function(){
	id = $(this).attr('data-id');
	$.getJSON('index.php?page=admin&subpage=users&update=1&id='+id, function(data){
		$('#mod_id').val(data.id);
		$('#mod_nome').val(data.nome);
		$('#mod_username').val(data.username);
		$('#mod_password').val(data.password);

		$("#mod_livello option").filter(function() {
    	return $(this).val() == data.livello; 
		}).prop('selected', true);
	});

	$('#modUtente').modal('toggle');
});

$('.addArti').click(function(){
	pos_madre = $(this).attr('data-pos-madre');
	posizione = $(this).attr('data-posizione');
	id_parte = $(this).attr('data-id-parte');
	id_modello = $(this).attr('data-id-modello');
	$('#add_pos_madre').val(pos_madre);
	$('#add_posizione').val(posizione);
	$('#add_id_parte').val(id_parte);
	$('#add_id_modello').val(id_modello);

	$('#addArticolo').modal('toggle');
});

$('#modSubmit').click(function(){
	$('#modForm').submit();
});

$('#addArticle').click(function(){
	$('#addForm').submit();
});


$('#modErase').click(function(){
	$('#mod_zen').val('0');
	$('#modForm').submit();
});