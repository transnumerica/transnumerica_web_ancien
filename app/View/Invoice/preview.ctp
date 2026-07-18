<div style="min-height:200px" >

</div>


<script>
  <?php 
    $sale_id_js = null;

    if(isset($sale_id)){
      if(is_numeric($sale_id)) $sale_id_js = $sale_id;
      else $sale_id_js = 'null';
    }else{
      $sale_id_js = 'null';
    }
      
  ?>
      
  let num_ticket_to_read = <?php echo $sale_id_js; ?>;
  if(num_ticket_to_read ==null) num_ticket_to_read = "FAUX";
    
</script>