var path    = require('path')
var webpack = require('webpack')
const Dotenv = require('dotenv-webpack')

module.exports = {
    entry: './src/main.js',
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    loaders: {'scss': 'vue-style-loader!css-loader!sass-loader',
                        'sass': 'vue-style-loader!css-loader!sass-loader?indentedSyntax'
                    }
                }
            },
            {
                test: /\.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/
            },
            {
                test: /\.css$/,
                loader: 'style-loader!css-loader'
            },
            {
                test: /\.(png|jpe?g|gif|svg)(\?\S*)?$/,
                loader: 'file-loader',
                query: {
                    name: '[name].[ext]?[hash]'
                }
            },
            {
                test: /\.(eot|svg|ttf|woff|woff2)(\?\S*)?$/,
                loader: 'file-loader'
            },
        ]
    },
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js',
            'styles': path.resolve(__dirname, './src/assets/sass'),
            'router': path.resolve(__dirname, './src/router'),
            'app': path.resolve(__dirname, './src/app'),
            'modules': path.resolve(__dirname, './src/app/modules'),
            'common': path.resolve(__dirname, './src/app/common'),
            'jquery': path.resolve(__dirname, './node_modules/jquery/dist/jquery.js')
        },
        extensions: ['*', '.js', '.vue']
    },
    devServer: {
        historyApiFallback: true,
        noInfo: true
    },
    performance: {
        hints: false
    },
    node: {
        fs: 'empty'
    },
    plugins: [
        new Dotenv({
            path:'./.env',
            sage: true
        }),

        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        })
    ]
}
