const path    = require('path')
const webpack = require('webpack')
const merge   = require('webpack-merge')
const common  = require('./webpack.common.js')
const HtmlWebpackPlugin = require('html-webpack-plugin')

module.exports = merge(common, {
    devtool: '#source-map',

    output: {
        path: path.resolve(__dirname, './dist', '[hash]'),
        publicPath: '/dist/[hash]/',
        filename: 'build.[hash].js',
    },

    plugins: (module.exports.plugins || []).concat([
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: '"production"'
            },
        }),
        new webpack.optimize.UglifyJsPlugin({
            sourceMap: true,
            compress: {
                warnings: false
            }
        }),
        new webpack.LoaderOptionsPlugin({
            minimize: true
        }),
        new HtmlWebpackPlugin({
            title: 'Ollyxpic - The best of Tibia tools',
            url: 'http://ollyxpic.com',
            filename: `${__dirname}/index.html`,
            template: `${__dirname}/template.html`,

        })
    ])
})
