
- (void)applicationDidFinishLaunching:(UIApplication *)application
{
  // Add registration for remote notifications
  [[UIApplication sharedApplication]
    registerForRemoteNotificationTypes:(UIRemoteNotificationTypeAlert | UIRemoteNotificationTypeBadge | UIRemoteNotificationTypeSound)];

  // Clear application badge when app launches
  application.applicationIconBadgeNumber = 0;
}

- (void)application:(UIApplication *)application didRegisterForRemoteNotificationsWithDeviceToken:(NSData *)deviceToken {
  [self sendProviderDeviceToken:deviceToken];

  NSLog(@"Did register for remote notifications: %@", deviceToken);

}

- (void)application:(UIApplication *)application didFailToRegisterForRemoteNotificationsWithError:(NSError *)error {

    NSLog(@"Fail to register for remote notifications: %@", error);

}

- (BOOL)sendProviderDeviceToken:(NSString *)devToken {

  // Get the users Display Name, Device Model, Device Token , Version Number.
    UIDevice *dev = [UIDevice currentDevice];
    NSString *deviceName = dev.name;
    NSString *deviceModel = dev.model;
    NSString *deviceSystemVersion = dev.systemVersion;

    NSString *deviceToken = [[[[devToken description]
                               stringByReplacingOccurrencesOfString:@"<"withString:@""]
                              stringByReplacingOccurrencesOfString:@">" withString:@""]
                             stringByReplacingOccurrencesOfString: @" " withString: @""];


    //CHANGE TO THE TOKEN SET IN SERVICE PAGE
    NSString *token = @"49dc30989de7381dfb4ed4374bd13f43";

    // URL of Service
    // CHANGE TO THE PATH OF SERVICE
    NSString *urlHost = @"www.mywebsite.com";
    // CHANGE THE NAME OF SERVICE PAGE
    NSString *queryString = [NSString stringWithFormat:@"/register.model.php?devicetoken=%@&devicename=%@&devicemodel=%@&deviceversion=%@&token=%@", deviceToken, deviceName, deviceModel,deviceSystemVersion , token];

    NSURL *url = [[NSURL alloc] initWithScheme:@"http" host:urlHost path:[queryString stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding]];
    NSURLRequest *request = [[NSURLRequest alloc] initWithURL:url];
    NSHTTPURLResponse *response = nil;
    NSError *error = nil;
    NSData *returnData = [NSURLConnection sendSynchronousRequest:request returningResponse:&response error:&error];

    if([response statusCode] != 200 ) return FALSE;
    return YES;

}