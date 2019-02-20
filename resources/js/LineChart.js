import { Line } from 'vue-chartjs'
export default ({
  extends: Line,
  mounted() {
      
        var d = new Date();
        var hh = d.getHours();
        var mm = d.getMinutes();

        var arr = [], i, j;
        for(j=0; j<60; j++) {
            if(mm-j >= 0){
                var min = (hh)+":"+(mm-j);                
                arr.push(min);

            } else {
                var min = (hh-1)+":"+(mm-j+60);                
                arr.push(min);
            }
        }
      
      axios.get('/history')
        .then(response => {
          
              this.renderChart({
                  labels: arr,

                  datasets: [
                    {
                      label: 'Last Hour Data',
                      backgroundColor: '#f87979',
                     // data: response.data.latestTasks.count
					  data: [4, 2, 12, 9, 1, 0, 3, 8, 0, 2, 1, 5]
                    }
                  ]
                })

            //this.tasks = response.data.tasks;
         
          console.log(response.data);

        });
  }
})