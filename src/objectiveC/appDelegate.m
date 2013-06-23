
- (void)applicationDidFinishLaunching:(UIApplication *)application
{
  // Add registration for remote notifications
  [[UIApplication sharedApplication]
    registerForRemoteNotificationTypes:(UIRemoteNotificationTypeAlert | UIRemoteNotificationTypeBadge | UIRemoteNotificationTypeSound)];

  // Clear application badge when app launches
  application.applicationIconBadgeNumber = 0;
}

- (void)application:(UIApplication *)application didRegisterForRemoteNotificationsWithDeviceToken:(NSData *)devToken {

  // Get the users Display Name, Device Model, Token & Version Number
  UIDevice *dev = [UIDevice currentDevice];
  NSString *deviceName = dev.name;
  NSString *deviceModel = dev.model;
  NSString *deviceSystemVersion = dev.systemVersion;

  NSString *deviceToken = [[[[devToken description]
    stringByReplacingOccurrencesOfString:@"<"withString:@""]
    stringByReplacingOccurrencesOfString:@">" withString:@""]
    stringByReplacingOccurrencesOfString: @" " withString: @""];

  // URL of Service
  // CHANGE TO THE PATH OF SERVICE
  NSString *urlHost = @"www.mywebsite.com/register.model.php";

  NSString *urlString = [NSString stringWithFormat:@"?devicetoken=%@&devicename=%@&devicemodel=%@&deviceversion=%@", deviceToken, deviceName, deviceModel, deviceSystemVersion;


  NSURL *url = [[NSURL alloc] initWithScheme:@"http" host:host path:[urlString stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding]];
  NSURLRequest *request = [[NSURLRequest alloc] initWithURL:url];
  NSData *returnData = [NSURLConnection sendSynchronousRequest:request returningResponse:nil error:nil];


}

- (void)application:(UIApplication *)application didFailToRegisterForRemoteNotificationsWithError:(NSError *)error {

    NSLog(@"Fail to register for remote notifications: %@", error);

}
