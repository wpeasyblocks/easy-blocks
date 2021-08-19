const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const BundleTracker = require('webpack-bundle-tracker');

// development mode
const devMode = process.env.NODE_ENV !== 'production';

module.exports = {
    context: __dirname,
    mode: devMode ? 'development' : 'production',
    entry: { blocks: './blocks/blocks.js' },
    // entry  : { './assets/js/editor.blocks': './blocks/blocks.js' },
    output : {
        path    : path.resolve(__dirname, 'assets'),
        filename: '[name].js',
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                // test: /\.js$|jsx/,
                exclude: [
                    path.resolve( __dirname, 'node_modules' ),
                    path.resolve( __dirname, 'assets' ),
                ],
                use: [
                    {
                        loader: 'babel-loader',
                        options: {
                            presets: [ '@babel/preset-react' ],
                        },
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
                                // indentWidth: 2,
                                includePaths: [
                                    path.resolve( __dirname, 'blocks/styles' )
                                ],
                                outputStyle: 'expanded',
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
        new MiniCssExtractPlugin({
            filename: devMode ? '[name].css' : '[name].min.css',
            chunkFilename: devMode ? '[id].css' : '[id].min.css',
        }),
        new BundleTracker({
            filename: 'logs/webpack-stats.json',
        })
    ],
};