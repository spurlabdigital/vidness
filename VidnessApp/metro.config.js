/**
 * Metro configuration for React Native
 * https://github.com/facebook/react-native
 *
 * @format
 */

const defaultAssetExts = require("metro-config/src/defaults/defaults").assetExts;


module.exports = {
    transformer: {
      getTransformOptions: async () => ({
        transform: {
          experimentalImportSupport: false,
          inlineRequires: true,
        },
      }),
    },

    resolver: {
      assetExts: [...defaultAssetExts, "obj", "mtl", "JPG", "vrx", "hdr", "gltf", "glb", "bin", "arobject", "gif", "jpeg"]
    }
  }

// 'use strict';

// const { getDefaultConfig } = require("metro-config");

// module.exports = (async () => {
//   const {
//     resolver: { assetExts }
//   } = await getDefaultConfig();

//   return {
//     resolver: {
//       assetExts: [...assetExts, "obj", "mtl", "JPG", "vrx", "hdr", "gltf", "glb", "bin", "arobject", "gif", "jpeg"]
//     }
//   };
// })();

// const { getDefaultConfig } = require("metro-config");


// module.exports = (async () => {
//   const {
//     resolver: { assetExts }
//   } = await getDefaultConfig();

//   return {
//     resolver: {
//       assetExts: [...assetExts, "obj", "mtl", "JPG", "vrx", "hdr", "gltf", "glb", "bin", "arobject", "gif", "jpeg"]
//     },
//     transformer: {
//       getTransformOptions: async () => ({
//         transform: {
//           experimentalImportSupport: false,
//           inlineRequires: true,
//         },
//       }),
//     },
//   };
// })();

// module.exports = {
//   transformer: {
//     getTransformOptions: async () => ({
//       transform: {
//         experimentalImportSupport: false,
//         inlineRequires: true,
//       },
//     }),
//   },

//   const {
//     resolver: { assetExts }
//   } = await getDefaultConfig();

//   resolver: {
//     assetExts: [...assetExts, "obj", "mtl", "JPG", "vrx", "hdr", "gltf", "glb", "bin", "arobject", "gif", "jpeg"]
//   },
// };
