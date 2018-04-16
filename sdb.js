var tableList = { //
	// pic : table名
	// PID : 主键名
	pic: 'PID',
	projoct: 'JID',
	pro_exp: 'EID',
	pro_work: 'WID',
	user: 'UID',
	pro_user: null,

};


function _SDB_(tList) {

	this.MYDAT    =   {};
	this.BOX = {};
	this.INDEX = {};
	//
	this.tableList = tList;

	this.jsonIN = function(D) {

		var o = this.tableList;
		for (var x in o) {
			if (o[x]) {
				this.jsonIN_1(x, o[x], D[x]);
			} else {
				this.jsonIN_null(x, D[x]);
			}
		}
	};

	// 有关键字的处理方法
	// 
	// T : table名
	// I : 主键名
	// A : 数据数组
	this.jsonIN_1 = function(T, I, A) {

		if (!A)
			return;
		if (!A.length)
			return;

		var o = this.BOX[T];
		if (!o)
			o = this.BOX[T] = {};

		for (var x in A)
			o[A[x][I]] = A[x];
	};

	// 没有关键字的处理方法
	// 
	// T : table名
	// I : 主键名 ( 这里没有 )
	// A : 数据数组
	this.jsonIN_null = function(T, A) {
		if (!A)
			return;
		if (!A.length)
			return;


		if (!this.BOX[T])
			this.BOX[T] = [];

		this.BOX[T] = this.BOX[T].concat(A)
	};
}


