<div class="form-group">
    <label for="exampleFormControlInput1">Results Per Page:</label>
    <input type="Nnumber" form="myForm" class="form-control" id="exampleFormControlInput1" name="results_per_page" min="1" max="<?php echo (count($results) > 0) ? strval(count($results)) : "10"; ?>">
</div>
