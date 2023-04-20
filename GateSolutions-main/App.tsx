/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow strict-local
 */

 import React from 'react';
 import {
   SafeAreaView,
   StyleSheet,
   ScrollView,
   View,
   Text,
   StatusBar,
   Dimensions,
   ActivityIndicator,
   BackHandler,
   TouchableOpacity,
   Image,
   
 } from 'react-native';
 import { WebView } from 'react-native-webview';

 const ScreenHeight = Dimensions.get("screen").height;
 const ScreenWidth = Dimensions.get("screen").width;
 
 // useEffect(() => {
 //   BackHandler.addEventListener('hardwareBackPress',()=>BackHandler.exitApp());
 //   // return () => {
 //   //   BackHandler.removeEventListener('hardwareBackPress', handleBackButtonClick);
 //   // };
 
 // }, []);
 
   
 class App extends React.Component {
 state={
   canGoBack:false,
   canGoForward:false,
   currentUrl:'https://yesdone.in/',
   webview:null,
   jscode:`window.postMessage(document.getElementById('info1-1g').style.display='none');`,
   spinner:false
 }
 componentDidMount=()=>{
   // console.log("DidMount")
   this.webviewRef = React.createRef()
   this.setState({webview:this.webviewRef})
   BackHandler.addEventListener('hardwareBackPress',this.handleBackButtonClick)
 }
 
 
 handleNavigationState = (navState)=>{
   
   let {canGoBack,canGoForward,url} = navState
   // console.log(url)
   this.setState({canGoBack,canGoForward,currentUrl:url})
 }
 
 handleBackButtonClick = ()=> {
   // console.log("Back Button")
   this.state.webview.current.goBack()
   return true
  
 }
 
 frontButtonHandler = () => {
   // console.log("Front Button")
     if (this.webviewRef.current) this.webviewRef.current.goForward()
   }
  render(){
 //    console.log("render")
 let jscode = 'document.getElementById("info1-1g").style.display="none"'
 const mySpinner = 
  <View >
     <ActivityIndicator
  color='#6592e6'
  size='large'
  // style={{flex:1,alignItems:"center",justifyContent:"center"}}
  />
  </View>

           
   return (
     <React.Fragment>
       <StatusBar barStyle="dark-content" />
       <SafeAreaView style={styles.container}>
        {/* {this.state.spinner&& mySpinner} */}
       


         <WebView source={{ uri:'https://gatesolutions.xyz' }} 
         style={styles.webview}
         ignoreSslError={true}
         startInLoadingState={true}
         allowsFullscreenVideo
         allowsInlineMediaPlayback
         mediaPlaybackRequiresUserAction 
         renderLoading={() => (
         <View style={{width:ScreenWidth,height:ScreenHeight,display:"flex",justifyContent:"center",alignItems:"center"}}>
           {/* <Image source={splash}  resizeMode="center" ></Image> */}
            {mySpinner}
         </View>
          
         )}
         ref={this.webviewRef}
         onNavigationStateChange={(navState)=>this.handleNavigationState(navState)}

         onLoadStart={()=>this.setState({spinner:true})}
         onLoad={()=>this.setState({spinner:false})}
         originWhitelist={['*']}
         // onLoadStart={(navState) => {console.log("load")}}
         scalesPageToFit={true}
         javaScriptEnabled={true}
         domStorageEnabled={true}
        //  injectedJavaScript={jscode}	
         onMessage={event=>{}}
         // userAgent="Mozilla/5.0 (Linux; Android 4.1.1; Galaxy Nexus Build/JRO03C) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19"
       />

{this.state.spinner && (
        <ActivityIndicator
          style={{ position: "absolute", top: ScreenHeight / 2, left: ScreenWidth / 2 }}
          size="large"
        />
      )}

       </SafeAreaView>
     </React.Fragment>
   );
  }
  
 };
 
 const styles = StyleSheet.create({
  container:{
   flex: 1,
 
  },
 
 });
 
 export default App;