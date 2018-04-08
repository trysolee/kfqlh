const DATE = {

	//
	day3: function(a) { // 返回当天和昨天,前天的范围

		var d = new Date(a);

		d.setHours(0, 0, 0, 0);
		d.setDate(d.getDate() + 1);

		var a = new Date(d);

		d.setDate(d.getDate() - 3);

		return {
			a: a, // x < a 
			b: d, // x > b
		};
	},
}



module.exports = DATE;