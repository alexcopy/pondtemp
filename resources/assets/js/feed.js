export default {
    data() {
        return {
            tankId: 0,
            ponds: [],
            feeds: {
                pellets: 0,
                sinking: 0
            },
            pondId: 'Pond',
            total: {},
            sortedTableData: {}
        }
    },
    mounted() {
        this.getFeeds(1);
    },
    methods: {
        getFeeds(days = 1) {
            axios.get('/api/getFeeds?days=' + days)
                .then(function (response) {
                    this.feeds = response.data;
                    this.ponds = response.data.ponds;
                    this.total = response.data.total;
                    this.sortedTableData=this.tableData();
                }.bind(this));
        },

        feed(foodType) {
            axios.put('/pond/feed', {foodType: foodType, pond: this.pondId, scoops: 1})
                .then(function (response) {
                    this.feeds = response.data;
                    this.ponds = response.data.ponds;
                    this.total = response.data.total;
                    this.sortedTableData=this.tableData();
                }.bind(this));
        },
        tableData() {
            var day = {};
            for (let i  in this.total) {
                var food = {sinkpellets: {count: 0, weight: 0}, pellets: {count: 0, weight: 0}};
                var daily = this.total[i];
                for (var k in daily) {
                    var feed = daily[k];
                    if (feed.food_type == "sinkpellets") {
                        food.sinkpellets.count += 1;
                        food.sinkpellets.weight += feed.weight;
                    } else if (feed.food_type == "pellets") {
                        food.pellets.count += 1;
                        food.pellets.weight += feed.weight;
                    }
                }
                day[i] = food;
            }
            return day;
        }
    },

}