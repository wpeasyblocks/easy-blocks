const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const BundleTracker = require('webpack-bundle-tracker');

// development mode
const devMode = process.env.NODE_ENV !== 'production';

// extract block styles
const BlockCssPlugin = new MiniCssExtractPlugin({
    filename: devMode ? '[name].css' : '[name].min.css'
});


module.exports = {
    context: __dirname,
    mode: devMode ? 'development' : 'production',
    entry: {
        blocks: './blocks/blocks.js',
    },
    output : {
        path    : path.resolve(__dirname, 'assets'),
        // filename: '[name].js',
    },
    module: {
        rules: [
            {
                test: /\.js$|jsx/,
                exclude: [
                    path.resolve( __dirname, 'node_modules' ),
                    path.resolve( __dirname, 'assets' ),
                ],
                use: [
                    {
                        loader: 'babel-loader',
                    },
                ],
            },
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                    },
                    {
                        loader: 'css-loader'
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sassOptions: {
                                indentType: "tab",
                                indentWidth: 1,
                            },
                        },
                    },
                    {
                        loader: 'postcss-loader',
                    }
                ],
            },
        ],
    },
    optimization: {
        minimizer: [
            new CssMinimizerPlugin(),
        ],
    },
    plugins: [
        BlockCssPlugin,
        // new BundleTracker({
        //     filename: 'logs/webpack-stats.json',
        // })
    ],
};