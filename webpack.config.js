module.exports = {
	entry: './js/oer_resource_block.js',
	output: {
		path: __dirname,
		filename: 'js/oer_resource_block.build.js',
	},
	module: {
		loaders: [
			{
				test: /.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/,
			},
		],
	},
};