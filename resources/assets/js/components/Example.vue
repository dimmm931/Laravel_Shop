<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading pointer" v-on:click="changeEntry()">Example Vue Component</div>

                    <div class="panel-body" :class="cssState? ' text-primary bg-danger' : ''"> <!-- change css based on props -->
                        I'm a Vue component example!!! </br>
						{{ myStateTextX }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>





<script>
    export default {
	    
		//i.e props
		data: function () {
            return {
                companies: [],
				myStateTextX: "I am a Vue state",
				cssState: false,
            }
        },
		
        mounted() {
            console.log('Component mounted.')
        },
		
		//method/functions
		methods: {
            changeEntry() {
                //if (confirm("Do you really want to proceed?")) {
				
                    var app = this;
					if(app.myStateTextX != 'Final state'){
					    app.myStateTextX = 'Final state';
						app.cssState = true;
					} else {
					    app.myStateTextX = 'State is changed';
						app.cssState = false;
					}
					
				//}
			}
		}
                    
    }
</script>
