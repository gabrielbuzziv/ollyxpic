<template>
    <page-load id="speedboost">
        <page-title>
            <img :src="image_path('item', 1)" class="margin-right-5">
            Exp
            <span class="margin-left-45">Share</span>
        </page-title>

        <div class="col-md-12">
            <panel class="form">
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" name="exp_three" id = "exp_three" value="3" />
                        <input type="hidden" name="exp_two" id = "exp_two" value="2" />
                        <input type="text" name="cost_per_day" id = "mytextfield" value="" />
                    </div>

                    <div class="col-md-6">



                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <p>Min lvl: <span class="min_lvl"></span></p>
                        <p>Max lvl: <span class="max_lvl"></span></p>
                    </div>
                </div>
            </panel>

            <script>
                $("#mytextfield").on('keyup',function(){
                    var min_divide= $(this).val() / $("#exp_three").val()
                    var min_multiply= min_divide * $("#exp_two").val()
                    var min_complete= min_multiply.toFixed()
        
                    var max_multiply= $(this).val() * $("#exp_three").val()
                    var max_divide= max_multiply / $("#exp_two").val()
                    var max_complete = max_divide.toFixed()
                $(".min_lvl").html(min_complete);
                $(".max_lvl").html(max_complete);
                })
            </script>

    </page-load>
</template>
