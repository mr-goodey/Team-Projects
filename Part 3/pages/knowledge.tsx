import jwt from "jsonwebtoken";
import { useRouter } from "next/router";
import { useEffect, useState } from "react";
import $ from "jquery";
import SidebarButton from "@/components/SidebarButton";
import Sidebar from "@/components/Sidebar";
import Head from "next/head";
import { useCookies } from "react-cookie";

export default function Knowledge() {
  const [userId, setUserId] = useState();
  const [cookies, setCookie] = useCookies(["token"]);
  const router = useRouter();
  useEffect(() => {
    const token = String(cookies.token);
    try {
      // json parse and stringify to please typescript
      let decoded = JSON.parse(
        JSON.stringify(jwt.verify(token, "your_jwt_secret"))
      );
      // set userId to state
      setUserId(decoded.userId);
      // change theme to user selected theme
      $("html").attr("data-theme", decoded.theme);
    } catch (e) {
      // if error with token send user to login page
      router.push("/login");
    }
  }, []);

  return (
    <>
      <Head>
        <title>Chat</title>
        <meta name="description" content="Make it all | Knowledge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/transparent_logo.png" />
      </Head>
      <Sidebar>
        <div className="flex h-screen">
          <div className="flex lg:hidden flex-grow-0 h-full p-2">
            <SidebarButton />
          </div>
          <div className="flex w-full h-full overflow-auto items-center flex-col justify-center bg-base-100 p-4">
            <section className="pt-20 pb-12 lg:pt-[120px] lg:pb-[90px]">
              <div className="flex flex-col justify-center items-center mx-auto ">
                <div className="-mx-4 flex flex-wrap">
                  <div className="w-full px-4">
                    <div className="mx-auto mb-12 flex items-center flex-col justify-center text-center lg:mb-20 h-full">
                      <img
                        className="w-28 h-28 max-h-28 mb-12"
                        src="transparent_logo.png"
                      />
                      <h2 className="text-dark mb-4 text-3xl font-bold sm:text-4xl md:text-[40px]">
                        Knowledge
                      </h2>
                      <p className="mb-12">Built by Group 21 for Make-It-All</p>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </Sidebar>
    </>
  );
}
