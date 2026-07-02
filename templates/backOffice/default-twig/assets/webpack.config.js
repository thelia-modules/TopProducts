const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('./dist/')
    .setPublicPath('/dist')
    .disableSingleRuntimeChunk()
    .addEntry('topProducts', './src/js/topProducts.js')
    .cleanupOutputBeforeBuild()
    .enableVersioning(false)
    .enableReactPreset()
    .enableSassLoader((options) => {
        options.api = 'modern-compiler';
        options.sassOptions = {
            ...(options.sassOptions || {}),
            quietDeps: true,
            silenceDeprecations: ['import'],
        };
    })
module.exports = Encore.getWebpackConfig();
