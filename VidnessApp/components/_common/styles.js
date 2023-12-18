
const React = require('react-native');

const { Platform } = React;
var { Dimensions } = require('react-native')

export default {
    container: {
        flex: 1,
        backgroundColor: '#ddd',
    },
    preview: {
        flex: 1
    },
    videoPlayer: { 
        position: 'absolute',
        left: 0,
        right: 0,
        top: 0,
        bottom: 0
    },
    singleMarker: {
        position: 'absolute', 
        width: 198, 
        height: 200,
        padding: 10,
        backgroundColor: 'rgba(0, 0, 0, 0.1)'
    },
    previewMarkerImage: {
        width: 178, 
        height: 100,
        marginBottom: 6
    },
    markerTitle: {
        fontSize: 14,
        color: 'white'
    },
    markerDate: {
        fontSize: 13,
        color: 'rgba(255, 255, 255, 0.7)'
    },
    markerCategory: {
        marginBottom: 3,
        fontWeight: 'bold',
        borderRadius: 3,
        borderWidth: 1,
        borderColor: 'white',
        color: 'white',
        paddingLeft: 10,
        paddingRight: 10,
        marginTop: 10,
        fontSize: 13

    },
    capture: {
        flex: 0,
        backgroundColor: '#fff',
        borderRadius: 5,
        color: '#000',
        padding: 10,
        margin: 40,
        marginBottom: 40
    },
    recordButtonWrap: {
        bottom: 60,
        position: 'absolute',
        right: 20,
        zIndex: 1,
        left: 20,
        height: 40,
        justifyContent: 'center'
    },
    recordButtonWrapAlt: {
        bottom: 60,
        position: 'absolute',
        right: 20,
        left: 0,
        height: 40
    },
    recordButtonHighlight: {
        width: 104,
        height: 28,
        position: 'absolute',
        right: 0
    },
    recordButton: {
        width: 104,
        height: 28
    },
    cancelButtonRight: {
        width: 20,
        height: 20,
        position: 'absolute',
        right: 0,
        top: 0
    },
    cancelRecordButton: {
        width: 20,
        height: 20
    },
    recordButtonLeft: {
        width: 104,
        height: 28,
        position: 'absolute',
        left: 0,
        top: 0
    },
    recordButtonAlt: {
        width: 104,
        height: 28
    },
    filterButton: {
        position: 'absolute',
        top: 60,
        right: 20,
        width: 40,
        height: 40,
        backgroundColor: '#fff',
        borderRadius: 20,
        borderWidth: 2,
        borderColor: '#ccc'
    },
    filterButtonIcon: {
        color: 'white',
        fontSize: 25,
        backgroundColor: 'transparent',
        marginLeft: 10,
        marginTop: 8
    },
    exploreInfo: {
        position: 'absolute', 
        color: 'white', 
        top: -4,
        left: 0,
        padding: 10,
        backgroundColor: 'rgba(255, 255, 255, 0.3)'
    },
    overlayInner: {
        marginTop: 40,
        padding: 20,
        backgroundColor: 'rgba(0, 0, 0, 0.8)',
        borderWidth: 2,
        borderRadius: 3,
        borderColor: 'rgba(255, 255, 255, 0.3)',
        zIndex: 999,
        maxWidth: 320,
    },
    uploadTargetOverlay: {
        flexDirection: 'column',
        alignItems: 'center'
    },
    markerIcon: {
        width: 40, 
        height: 49
    },
    inputWrap: {
        padding: 3,
        backgroundColor: 'white',
        borderRadius: 4,
        paddingLeft: 10,
        paddingRight: 10,
        marginBottom: 4
    },
    submitField: {
        height: 40,
        backgroundColor: 'white'
    },
    categorySelector: {
        height: 40,
        marginLeft: (Platform.OS == 'ios') ? -16 : 0,
        fontSize: 14
    },
    submitButton: {
        backgroundColor: '#46bfe0',
        marginTop: 20
    },
    rowButton: {
        backgroundColor: '#46bfe0',
        marginTop: 20,
        marginRight: 10
    },
    rowButtonGrey: {
        backgroundColor: '#ddd',
        marginTop: 20,
        marginRight: 10
    },
    submitButtonOverlay: {
        backgroundColor: '#46bfe0',
        marginTop: 10
    },
    submitButtonText: {
        color: 'white'
    },
    markerIconContainer: {
        alignItems: 'center',
        justifyContent: 'center',
        marginBottom: 10
    },
    overlayHeading: {
        textAlign: 'center',
        fontSize: 24,
        color: 'white',
        marginBottom: 10
    },
    whiteText: {
        color: 'white',
        textAlign: 'center',
        marginBottom: 10
    },
    greyText: {
        color: 'rgba(255, 255, 255, 0.7)',
        textAlign: 'center'
    },


    channelRow: {
        marginBottom: 10,
        paddingBottom: 10,
        borderBottomWidth: 1,
        borderBottomColor: '#ddd'
    },
    channelTitle: {
        fontSize: 16,
        fontWeight: '800',
        marginBottom: 4,
        color: '#46bfe0'
    },
    channelDescription: {
        color: '#777'
    },
    selectedChannelText: {
        marginBottom: 3,
        fontWeight: 'bold',
        borderRadius: 3,
        borderWidth: 1,
        paddingLeft: 10,
        paddingRight: 10,
        paddingTop: 3,
        paddingBottom: 3,
        marginTop: 4,
        marginBottom: 4,
        fontSize: 13,
        width: 79,
        textAlign: 'center'
    },
    floatingButtonWrap: {
        alignItems: "center",
        position: "absolute",
        justifyContent: "center",
        bottom: 50,
        left: 0,
        right: 0, 
        flexDirection: 'row'
    },
    floatingButton: {
        backgroundColor: "#BD9A88",
        marginBottom: 8,
    },
    largeButtonText: {
        fontSize: 30,
        paddingTop: 6,
        paddingBottom: 6,
        color: 'white'
    },
    mapModal: {
        position: "absolute",
        left: 60,
        right: 60,
        top: 60,
        bottom: 100
    },
    mapOverlay: {
        width: "100%",
        height: Dimensions.get('window').height
    },
    mapButton: {
        width: 48,
        height: 48,
        position: 'absolute',
        left: 30,
        bottom: 60,
        zIndex: 2
    },
    mapButtonImage: {
        width: 48,
        height: 48,
    },
    menuButton: {
        width: 48,
        height: 48,
        position: 'absolute',
        left: 30,
        top: 60
    },
    menuButtonImage: {
        width: 48,
        height: 48,
    },
    controlButton: {
        width: 48,
        height: 48,
        position: 'absolute',
        right: 30,
        bottom: 60
    },
    controlButtonImage: {
        width: 48,
        height: 48,
    },
    bodyText: {
        fontSize: 16,
        lineHeight: 24,
        fontWeight: '300',
        marginBottom: 20
    },
    exploreOverlayButton: {
        width: 128,
        height: 48,
        position: 'absolute',
        left: 20,
        bottom: 60,
    },
    exploreOverlayButtonImage: {
        width: 128,
        height: 48,
    },
    mapOverlayButton: {
        width: 105,
        height: 48,
        position: 'absolute',
        left: 30,
        bottom: 60,
    },
    mapOverlayButtonImage: {
        width: 105,
        height: 48,
    },
    calloutWrapper: {
        padding: 10,
        width: 200
    },
    calloutTitle: {
        fontSize: 16,
        fontWeight: 'bold',
        marginBottom: 4,
    },
    calloutSubtitle: {
        fontSize: 15,
    },
    calloutPreviewImage: {
        width: 180,
        height: 120,
        marginBottom: 8
    },
    closeButton: {
        width: 48,
        height: 48,
        position: 'absolute',
        right: 30,
        top: 60
    },
    closeButtonImage: {
        width: 48,
        height: 48,
    },
    calloutCategory: {
        fontSize: 14,
        fontWeight: 'bold',
        marginBottom: 3,
    },
    calloutDate: {
        fontSize: 14,
        color: '#999999',
        marginBottom: 4,
    }
};
