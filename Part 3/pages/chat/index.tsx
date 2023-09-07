import jwt from "jsonwebtoken";
import { useRouter } from "next/router";
import { FC, useEffect, useState } from "react";
import $ from "jquery";
import DisplayChat from "@/components/DisplayChat";
import ViewChats from "@/components/ViewChats";
import Sidebar from "@/components/Sidebar";
import Head from "next/head";
import axios from "axios";
import { useCookies } from "react-cookie";
import SidebarButton from "@/components/SidebarButton";

type Chats = {
  chats: {
    chats: [
      {
        chat: {
          id: number;
          name: string;
          description: string;
          users: [
            {
              user: {
                id: number;
                username: string;
              };
            }
          ];
          messages: [
            {
              text: string;
              timestamp: string;
              user: {
                username: string;
                id: number;
              };
            }
          ];
        };
      }
    ];
  };
};

const Chat: FC<Chats> = ({ chats }) => {
  const [userId, setUserId] = useState(0);
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
        <meta name="description" content="Make it all chat page" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/transparent_logo.png" />
      </Head>
      <Sidebar>
        <div className="flex h-screen">
          <div className="flex lg:hidden flex-grow-0 h-full p-2">
            <SidebarButton />
          </div>
          <div className="flex shrink-0 basis-1/2 md:basis-1/3 lg:basis-1/2 xl:basis-1/3 2xl:basis-1/4 h-screen bg-base-100 border-r">
            <ViewChats chats={chats} userId={userId} />
          </div>
          <div className="flex flex-col h-screen bg-base-100 flex-grow">
            <DisplayChat chat={null} userId={userId} textInputStatus={false} />
          </div>
        </div>
      </Sidebar>
    </>
  );
};

export async function getServerSideProps(context: any) {
  const token = String(context.req.cookies.token);
  console.log(token);
  try {
    // json parse and stringify to please typescript
    jwt.verify(token, "your_jwt_secret");
    const userId: number = JSON.parse(JSON.stringify(jwt.decode(token))).userId;

    const res = await axios.get(`${process.env.HOST}/api/chats`, {
      data: { userId },
    });
    return {
      props: {
        chats: res.data.data,
      },
    };
  } catch (e) {
    // if error with token send user to login page
    console.log(e);
    return {
      redirect: {
        destination: "/login",
        permanent: false,
      },
    };
  }
}

export default Chat;
