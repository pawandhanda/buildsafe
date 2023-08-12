<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous"><script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<div id="build-cost-app">
    <div>
        <div class="form-group">
            <label for="customRange1" class="form-label">Build Cost</label>
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1">£</span>
                  <input type="text" class="form-control"  v-model="formData.build_cost"  @input="currenyFormatter(this.formData.build_cost)" aria-label="Username" aria-describedby="basic-addon1">
                </div>
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1" class="form-label">Build Type</label>
            <select class="form-control" v-model="formData.build_type" v-on:change="checkValue()">
                <option value="">- Select -</option>
                <option value="new_build">New Build</option>
                <option value="build_cost_conversion">Build Cost Conversion</option>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1" class="form-label">Stage</label>
            <select class="form-control" v-model="formData.stage" v-on:change="checkValue()">
                <option value="">- Select -</option>
                <option value="not_started">Not Started</option>
                <option value="part_completed">Part Complete</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div class="form-group mb-0">
            <button class="btn btn-primary btn-submit" type="button" v-on:click="calculateBuildCost()" :disabled="loading == true">Calculate</button>
        </div>
        <div id="build-cost-app-results" class="mt-5" v-if="build_result">
            <div class="alert alert-warning text-center">
                Calculated Indicative Cost:  <strong>{{ build_result }}</strong>
            </div>
        </div>

      </div>
</div>

<script>
  const { createApp } = Vue;createApp({
    data() {
      return {
        calculatorData: {
            not_started: { new_build: 1, build_cost_conversion: 1.20},
            part_completed: { new_build: 1.75, build_cost_conversion: 2},
            completed: { new_build: 2.50, build_cost_conversion: 2.75},
        },
        formData:{
            build_type: '',
            stage: '',
            build_cost: '',
            formatted_build_cost: '£0.00'
        },
        loading: true,
        build_result: '',
      }
    },
    methods:{
        calculateBuildCost(){
            const build_type = this.formData.build_type;
            const stage = this.formData.stage;

            if( build_type && stage ){
                const final_cost_percentage = this.calculatorData[stage][build_type];
                if(final_cost_percentage){

                    const value = this.formData.build_cost.replace(/\,/g,'')
                    const formatter = new Intl.NumberFormat('en-GB', {
                        style: 'currency',
                        currency: 'GBP',
                    });
                    this.build_result = formatter.format((value * final_cost_percentage)/100) ;
                }
            }
        },
        checkValue(){
            if(this.formData.build_type && this.formData.stage ){
                this.loading = false;
            }else{
                this.loading = true;
            }
        },
        currenyFormatter(value){
            console.log(value);
            value = value.replace(/\,/g,'')
            const formatter = new Intl.NumberFormat('en-GB', {
            });
            this.formData.build_cost = formatter.format(value);
            return this.formData.build_cost;
        }
    },
    mounted(){
        this.formData.build_cost = '';
    }
  }).mount('#build-cost-app')
</script>

<style scoped>
    #build-cost-app{
        padding: 25px;
        border: 1px solid #dadbdd;
        border-radius: 5px;
        max-width: 500px;
        font-size: 16px;
    }
    #build-cost-app .form-group{
        margin-bottom: 30px;
    }
    #build-cost-app .input-group .input-group-text{
        padding: 5px 15px;
        text-align: center;
        font-size: 14px;
        font-weight: bold;
    }
    #build-cost-app .input-group::focus{
        outline: none !important;
    }
    #build-cost-app .form-control:focus{
        outline: none !important;
        box-shadow: none;
    }
    #build-cost-app .form-control{
        border-color: #dadbdd;
        min-height: 40px;
        font-size: 16px;
        padding: 0 15px;
        line-height: 40px;
    }
    #build-cost-app .form-group label{
        font-weight: bold;
    }
    #build-cost-app .form-group select{
        min-height: 40px;
        font-size: 16px;
        line-height: 40px;
        padding: 0 15px;
        border-color: #dadbdd;
    }
    #build-cost-app .btn-submit{
        background: #A4CD53;
        color: #FFF;
        border: 0;
        padding: 15px 30px;
    }
</style>