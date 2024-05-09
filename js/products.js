        let which=true;
        let SwitchColor1="red";
        let SwitchColor2="black";
        document.getElementById('change-text-color').addEventListener('click', function() {
            var productNames = document.querySelectorAll('.product-name');
            var products=document.querySelectorAll('.product');
            var productP=document.querySelectorAll('.product p');
            var productB=document.querySelectorAll('.product button');
            if(which){
                productP.forEach(function(element){
                    element.style.color=SwitchColor1;
                })
                productB.forEach(function(element){
                    element.style.color=SwitchColor1;
                })
                products.forEach(function(element) {
                    element.style.backgroundColor="Black";
                    element.style.color=SwitchColor1;
                });
                productNames.forEach(function(element) {
                    element.style.color=SwitchColor1;
                });
                which=false;
            }else{
                productP.forEach(function(element){
                    element.style.color="#666";
                })
                productB.forEach(function(element){
                    element.style.color="white";
                })
                products.forEach(function(element) {
                    element.style.backgroundColor="White";
                    element.style.color=SwitchColor2;
                });
                productNames.forEach(function(element) {
                    element.style.color=SwitchColor2;
                });
                which=true;
            }
        });
        

        document.getElementById('reset').addEventListener('click', function() {
            var removeit = document.getElementById('removethis');
            if(removeit!=null)
                removeit.remove();
        });
